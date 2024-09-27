<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TelementriTMAController extends Controller
{
    public function index()
    {
        return view('pages.telementri.tmas.index');
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

            return view('pages.telementri.tmas.show', compact('sensorView', 'labels', 'values', 'sensor_company_id', 'start', 'end'));
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function generatePDF(Request $request)
    {
        $request->validate([
            'g-recaptcha-response' => 'recaptcha',
        ]);

        $client = new Client();

        $token = $token = Cache::get('api_token');
        $url = env('URL_API') . '/sensors/records';

        try {
            $start = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
            $end = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');

            if (!empty($start) && !empty($end)) {
                $start = Carbon::parse($request->start)->format('Y-m-d H:i:s');
                $end = Carbon::parse($request->end)->format('Y-m-d H:i:s');
            }

            $params = [
                'sensor_company_id' => $request->sensor_company_id,
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

            if (!empty($sensorRecords['data']['data']) && isset($sensorRecords['data']['data'][0]['sensor_records'])) {
                $sensorView = $sensorRecords['data']['data'][0]['sensor_records'];
            } else {
                $sensorView = [];
            }

            $pdf = Pdf::loadView('pages.pdf.telementri_tma', compact('sensorView'));
            $pdf->setBasePath(public_path());

            return $pdf->download('Telementri Tinggi Muka Air BWS Sumatra VI.pdf');
        } catch (Exception $e) {
            throw $e;
        }
    }
}
