<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\Promise as PromisePromise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TelementriController extends Controller
{
    private $client;
    private $token;

    public function __construct()
    {
        $this->client = new Client();
        $this->token = $this->getToken();
    }

    private function getToken()
    {
        $loginUrl = env('URL_API') . '/users/login';
        $credentials = [
            'strategy' => 'web',
            'email' => 'febri.kfc@gmail.com',
            'password' => 'Vzucva0Z'
        ];

        try {
            $response = $this->client->request('POST', $loginUrl, [
                'json' => $credentials
            ]);

            $data = json_decode($response->getBody(), true);
            Cache::put('api_token', $data['data']['accessToken'], 3600);
            return $data['data']['accessToken'];
        } catch (\Exception $e) {
            throw new \Exception("Failed to login: " . $e->getMessage());
        }
    }

    private function makeRequest($method, $url, $params)
    {
        try {
            $response = $this->client->request($method, $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ],
                'query' => $params
            ]);
            return $response;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if ($e->getResponse()->getStatusCode() == 401) {
                $this->token = $this->getToken();
                $response = $this->client->request($method, $url, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->token
                    ],
                    'query' => $params
                ]);
                return $response;
            }
            throw $e;
        }
    }

    public function tmas()
    {
        $device_id = "MTI-VSRQH6HIXQIQ";
        $urlDevice = env('URL_API') . '/devices';
        $urlSensor = env('URL_API') . '/sensors/records';

        $params1 = [
            'company_id' => env('COMPANY_ID'),
            'device_id' => $device_id
        ];

        try {
            $responseDevice = $this->makeRequest('GET', $urlDevice, $params1);
            $devices = json_decode($responseDevice->getBody(), true);
            $devicesDetail = $devices['data']['data'][0];

            $sensor_company_id = "97e9bcf6-94a5-4c0d-8c77-f5cc80630770";
            $start = Carbon::now('Asia/Jakarta')->subDay()->setTime(18, 0, 0)->format('Y-m-d H:i:s');
            $end = Carbon::now('Asia/Jakarta')->endOfDay()->format('Y-m-d H:i:s');

            $params2 = [
                'sensor_company_id' => $sensor_company_id,
                'start' => $start,
                'end' => $end
            ];

            $responseSensor = $this->makeRequest('GET', $urlSensor, $params2);
            $sensorRecord = json_decode($responseSensor->getBody(), true);

            if (isset($sensorRecord['data']['data'][0]['sensor_records']) && !empty($sensorRecord['data']['data'][0]['sensor_records'])) {
                usort($sensorRecord['data']['data'][0]['sensor_records'], function ($a, $b) {
                    return strtotime($b['datetime']) - strtotime($a['datetime']);
                });

                $latestSensorRecord = $sensorRecord['data']['data'][0]['sensor_records'];
            } else {
                $latestSensorRecord = [];
            }

            $newData = [];
            foreach ($latestSensorRecord as $sr) {
                $newData[] = [
                    "device_id" => $sr['device_id'],
                    "value_calibration" => $sr['value_calibration'],
                    "datetime" => $sr['datetime'],
                    "name_alias" => $devicesDetail['name_alias'],
                    "gps_location_lat" => $devicesDetail['gps_location_lat'],
                    "gps_location_lng" => $devicesDetail['gps_location_lng'],
                    "sensor_company_id" => $sensor_company_id,
                ];
            }

            if (count($newData) > 0) {
                return response()->json(['data' => $newData[0]]);
            } else {
                $newData = [
                    "name_alias" => $devicesDetail['name_alias'],
                    "device_id" => $devicesDetail['device_id'],
                    "datetime" => "-",
                    "value_calibration" => 0,
                    "gps_location_lat" => $devicesDetail['gps_location_lat'],
                    "gps_location_lng" => $devicesDetail['gps_location_lng'],
                    "sensor_company_id" => $sensor_company_id,
                ];
                return response()->json(['data' => $newData]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function crhs()
    {
        $client = new Client();

        $token = 'Bearer ' . env('TOKEN_API');
        $urlDevice = env('URL_API') . '/devices';

        $params1 = [
            'company_id' => env('COMPANY_ID'),
        ];

        try {
            $responseDevice = $this->makeRequest('GET', $urlDevice, $params1);

            $devices = json_decode($responseDevice->getBody(), true);
            $devicesDetail = $devices['data']['data'];

            $limitedDevices = array_slice($devicesDetail, 0, 4);


            $newDevice = [];
            foreach ($limitedDevices as $device) {
                $newDevice[] = $device;
            }

            $sensorData = [];

            foreach ($newDevice as $index => $value) {
                if ($index == 0) {
                    $sensor_company_id = "f94b6825-72cd-48d0-8f1e-ce2cc4e6bdaa";
                } else if ($index == 1) {
                    $sensor_company_id = "7a8386b9-c4be-452a-9671-c4b590affe06";
                } else if ($index == 2) {
                    $sensor_company_id = "a44d5fad-22d7-45b7-8a81-5301fd325032";
                } else if ($index == 3) {
                    $sensor_company_id = "e4f08a05-8fa7-40ec-994c-d31ee9d8034c";
                }

                $sensorData[] = [
                    "device_id" => $value['device_id'],
                    "name_alias" => $value['name_alias'],
                    "gps_location_lat" => $value['gps_location_lat'],
                    "gps_location_lng" => $value['gps_location_lng'],
                    "sensor_company_id" => $sensor_company_id,
                ];
            }

            $urlRecords = env('URL_API') . '/sensors/records';
            $start = Carbon::now('Asia/Jakarta')->subDay()->setTime(18, 0, 0)->format('Y-m-d H:i:s');
            $end = Carbon::now('Asia/Jakarta')->endOfDay()->format('Y-m-d H:i:s');

            $currentDateTime = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');

            $sensorRecordNew = [];

            foreach ($sensorData as $sd) {
                $params2 = [
                    'sensor_company_id' => $sd['sensor_company_id'],
                    'start' => $start,
                    'end' => $end
                ];

                $responseSensor = $client->request('GET', $urlRecords, [
                    'headers' => [
                        'Authorization' => $this->token
                    ],
                    'query' => $params2
                ]);

                $sensorRecord = json_decode($responseSensor->getBody(), true);

                if (
                    isset($sensorRecord['data']['data'][0]['sensor_records']) &&
                    !empty($sensorRecord['data']['data'][0]['sensor_records'])
                ) {

                    usort($sensorRecord['data']['data'][0]['sensor_records'], function ($a, $b) {
                        return strtotime($b['datetime']) - strtotime($a['datetime']);
                    });

                    $latestSensorRecord = $sensorRecord['data']['data'][0]['sensor_records'][0];
                } else {
                    $latestSensorRecord = [];
                }

                if (!empty($latestSensorRecord)) {
                    $sensorRecordNew[] = [
                        "device_id" => $sd['device_id'],
                        "value_calibration" => $latestSensorRecord['value_calibration'],
                        "datetime" => $latestSensorRecord['datetime'],
                        "name_alias" => $sd['name_alias'],
                        "gps_location_lat" => $sd['gps_location_lat'],
                        "gps_location_lng" => $sd['gps_location_lng'],
                        "sensor_company_id" => $sd['sensor_company_id'],
                    ];
                }
            }

            return response()->json(['data' => $sensorRecordNew]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
