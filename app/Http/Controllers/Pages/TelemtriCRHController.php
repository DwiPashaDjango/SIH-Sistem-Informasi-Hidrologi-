<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TelemtriCRHController extends Controller
{
    public function index()
    {
        return view('pages.telementri.crh.index');
    }

    public function show(Request $request, $sensor_company_id)
    {
        $client = new Client();

        $token = $token = Cache::get('api_token');
        $url = env('URL_API') . '/sensors/records';

        try {
            if (!empty($request->start) && !empty($request->end)) {
                $start = Carbon::parse($request->start)->format('Y-m-d H:i:s');
                $end = Carbon::parse($request->end)->format('Y-m-d H:i:s');
            } else {
                $start = Carbon::now('Asia/Jakarta')->subDay()->setTime(18, 0, 0)->format('Y-m-d H:i:s');
                $end = Carbon::now('Asia/Jakarta')->endOfDay()->format('Y-m-d H:i:s');
            }

            $params = [
                'sensor_company_id' => $sensor_company_id,
                'start' => $start,
                'end' => $end,
            ];

            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => $token
                ],
                'query' => $params
            ]);

            $sensorRecords = json_decode($response->getBody(), true);

            $sensorView = [];
            $labels = [];
            $values = [];

            if (!empty($sensorRecords['data']['data']) && isset($sensorRecords['data']['data'][0]['sensor_records'])) {
                $sensorView = $sensorRecords['data']['data'][0]['sensor_records'];

                foreach ($sensorView as $record) {
                    $labels[] = Carbon::parse($record['datetime'])->format('Y-m-d H:i:s');
                    $values[] = $record['value_calibration'];
                }
            } else {
                $sensorView = [];
            }

            return view('pages.telementri.crh.show', compact('sensorView', 'labels', 'values', 'sensor_company_id', 'start', 'end'));
        } catch (Exception $e) {
            throw $e;
        }
    }
}
