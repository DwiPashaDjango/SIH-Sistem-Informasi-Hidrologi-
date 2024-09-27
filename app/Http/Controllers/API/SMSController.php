<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\CurahHujan;
use App\Models\Klimatologi;
use App\Models\Sms;
use App\Models\TMA;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class SMSController extends Controller
{
    public function coba(Request $request)
    {
        $tanggal = $request->get('datetime');
        $newTanggal = Carbon::createFromFormat('Ymd-His',  $tanggal);
        dd($newTanggal->subDays(1)->toDateString());
    }

    public function index(Request $request)
    {
        $idsms = $request->get('idsms');
        $sender = $request->get('sender');
        $content = $request->get('content');
        $tanggal = $request->get('datetime');
        $newContent = explode(":", $content);

        if ($tanggal && strlen($tanggal) == 15) {
            if (str_split($tanggal)[8] == "-") {
                $newTanggal = Carbon::createFromFormat('Ymd-His',  $tanggal);
            }
        } else {
            $newTanggal = Carbon::today();
        }

        //cek user
        $user = User::where('nohp', $sender)->with('pos')->first();
        if (!$user->pos) {
            return ResponseFormatter::error($sms, 'SMS Gateway Gagal', 401);
        }
        $apiURLSalah = 'http://secure.gosmsgateway.com/api/send.php?username=hkabwssumvi&mobile=' . $sender . '&message=Maaf!%20format%20yang%20Anda%20kirimkan%20tidak%20sesuai%2C%20silahkan%20ulangi%20lagi.%0APanduan%20SMS%20%3A%20http%3A%2F%2Fhkabwssumvi.id%2Fpanduansms&password=gosms99384';

        $textTMA = "Terima%20kasih%2C%20pengiriman%20data%20yang%20Anda%20kirimkan%20telah%20kami%20terima%20dan%20sesuai%20format.%0AData%20TMA%20" . $user->pos->nama . "%20%3A%20" . $newTanggal;

        $apiURLTMA = 'http://secure.gosmsgateway.com/api/send.php?username=hkabwssumvi&mobile=' . $sender . '&message=' . $textTMA . '&password=gosms99384';

        $textCurah = "Terima%20kasih%2C%20pengiriman%20data%20yang%20Anda%20kirimkan%20telah%20kami%20terima%20dan%20sesuai%20format.%0AData%20Curah%20Hujan%20" . $user->pos->nama . "%20%3A%20" . $newTanggal;

        $apiURLCurah = 'http://secure.gosmsgateway.com/api/send.php?username=hkabwssumvi&mobile=' . $sender . '&message=' . $textCurah . '&password=gosms99384';

        $textKlimatologi = "Terima%20kasih%2C%20pengiriman%20data%20yang%20Anda%20kirimkan%20telah%20kami%20terima%20dan%20sesuai%20format.%0AData%20Klimatologi%20" . $user->pos->nama . "%20%3A%20" . $newTanggal;

        $apiURLKlimatologi = 'http://secure.gosmsgateway.com/api/send.php?username=hkabwssumvi&mobile=' . $sender . '&message=' . $textKlimatologi . '&password=gosms99384';



        $sms = Sms::create([
            'idsms' => $idsms,
            'sender' => $sender,
            'content' => $content,
            'tanggal' => $newTanggal,
        ]);


        if (!empty($user)) {

            //cek jenis
            if ($user->pos->jenis_id === "1") {
                //SESI CURAH HUJAN

                //cek string

                if (count($newContent) == 2 || count($newContent) == 3) {

                    if (ctype_alpha($newContent[0]) || ctype_alpha($newContent[1])) {
                        Http::get($apiURLSalah);
                        return ResponseFormatter::error(
                            $sms,
                            'Maaf Format SMS Salah. Harap Menggunakan Angka bukan Huruf',
                            401
                        );
                    }

                    $curah_hujan = CurahHujan::where('pos_id', $user->pos_id)->where('tanggal', $newTanggal->subDays(1)->toDateString())->first();

                    if ($curah_hujan) {
                        $curah_hujan->hujan_otomatis = floatval($newContent[0]);
                        $curah_hujan->hujan_biasa = floatval($newContent[1]);
                        $curah_hujan->keterangan = count($newContent) == 3 ? $newContent[2] : null;
                        $curah_hujan->update();

                        Http::get($apiURLCurah);

                        return ResponseFormatter::success(
                            [$sms, $curah_hujan],
                            'Data SMS dan Curah Hujan Berhasil disimpan'
                        );
                    } else {
                        $data = CurahHujan::create([
                            'tanggal' => $newTanggal->subDays(1),
                            'hujan_otomatis' => floatval($newContent[0]),
                            'hujan_biasa' => floatval($newContent[1]),
                            'keterangan' => count($newContent) == 3 ? $newContent[2] : null,
                            'pos_id' => $user->pos_id,
                        ]);

                        Http::get($apiURLCurah);

                        return ResponseFormatter::success(
                            [$sms, $data],
                            'Data SMS dan Curah Hujan Berhasil disimpan'
                        );
                    }
                } else {
                    Http::get($apiURLSalah);
                    return ResponseFormatter::error(
                        $sms,
                        'Maaf Format SMS Salah Silahkan dicoba lagi',
                        401
                    );
                }
            } else if ($user->pos->jenis_id === "2") {

                //SESI TMA
                //cek length

                if (count($newContent) == 3 || count($newContent) == 4) {

                    if (ctype_alpha($newContent[0]) || ctype_alpha($newContent[1]) || ctype_alpha($newContent[2])) {


                        Http::get($apiURLSalah);

                        return ResponseFormatter::error(
                            $sms,
                            'Maaf Format SMS Salah. Harap Menggunakan Angka bukan Huruf',
                            401
                        );
                    }

                    $tma = TMA::where('pos_id', $user->pos_id)->where('tanggal', $newTanggal->toDateString())->first();

                    if ($tma) {
                        $tma->pagi = floatval($newContent[0]);
                        $tma->siang = floatval($newContent[1]);
                        $tma->sore = floatval($newContent[2]);
                        $tma->keterangan = count($newContent) == 4 ? $newContent[3] : null;
                        $tma->update();

                        Http::get($apiURLTMA);

                        return ResponseFormatter::success(
                            [$sms, $tma],
                            'Data SMS dan TMA Berhasil disimpan'
                        );
                    } else {
                        $data = TMA::create([
                            'tanggal' => $newTanggal,
                            'pagi' => floatval($newContent[0]),
                            'siang' => floatval($newContent[1]),
                            'sore' => floatval($newContent[2]),
                            'keterangan' => count($newContent) == 4 ? $newContent[3] : null,
                            'pos_id' => $user->pos_id,
                        ]);

                        Http::get($apiURLTMA);

                        return ResponseFormatter::success(
                            [$sms, $data],
                            'Data SMS dan TMA Berhasil disimpan'
                        );
                    }
                } else {
                    Http::get($apiURLSalah);
                    return ResponseFormatter::error(
                        $sms,
                        'Maaf Format SMS Salah Silahkan dicoba lagi',
                        401
                    );
                }
            } else if ($user->pos->jenis_id  === "3") {
                //SESI KLIMATOLOGI
                //cek length
                if (count($newContent) == 21 || count($newContent) == 22) {

                    if (ctype_alpha($newContent[0]) || ctype_alpha($newContent[1]) || ctype_alpha($newContent[2]) || ctype_alpha($newContent[3]) || ctype_alpha($newContent[4]) || ctype_alpha($newContent[5]) || ctype_alpha($newContent[6]) || ctype_alpha($newContent[7]) || ctype_alpha($newContent[8]) || ctype_alpha($newContent[9]) || ctype_alpha($newContent[10]) || ctype_alpha($newContent[11]) || ctype_alpha($newContent[12]) || ctype_alpha($newContent[13]) || ctype_alpha($newContent[14]) || ctype_alpha($newContent[15]) || ctype_alpha($newContent[16]) || ctype_alpha($newContent[17]) || ctype_alpha($newContent[18]) || ctype_alpha($newContent[19]) || ctype_alpha($newContent[20])) {

                        Http::get($apiURLSalah);
                        return ResponseFormatter::error(
                            $sms,
                            'Maaf Format SMS Salah. Harap Menggunakan Angka bukan Huruf',
                            401
                        );
                    }

                    $klimatologi = Klimatologi::where('pos_id', $user->pos_id)->where('tanggal', $newTanggal->toDateString())->first();

                    if ($klimatologi) {
                        $klimatologi->termo_max_pagi = floatval($newContent[0]);
                        $klimatologi->termo_max_siang = floatval($newContent[1]);
                        $klimatologi->termo_max_sore = floatval($newContent[2]);
                        $klimatologi->termo_min_pagi = floatval($newContent[3]);
                        $klimatologi->termo_min_siang = floatval($newContent[4]);
                        $klimatologi->termo_min_sore = floatval($newContent[5]);
                        $klimatologi->bola_kering_pagi = floatval($newContent[6]);
                        $klimatologi->bola_kering_siang = floatval($newContent[7]);
                        $klimatologi->bola_kering_sore = floatval($newContent[8]);
                        $klimatologi->bola_basah_pagi = floatval($newContent[9]);
                        $klimatologi->bola_basah_siang = floatval($newContent[10]);
                        $klimatologi->bola_basah_sore = floatval($newContent[11]);
                        $klimatologi->rh = floatval($newContent[12]);
                        $klimatologi->termo_apung_max = floatval($newContent[13]);
                        $klimatologi->termo_apung_min = floatval($newContent[14]);
                        $klimatologi->penguapan_plus = floatval($newContent[15]);
                        $klimatologi->penguapan_min = floatval($newContent[16]);
                        $klimatologi->anemometer_spedometer = floatval($newContent[17]);
                        $klimatologi->hujan_otomatis = floatval($newContent[18]);
                        $klimatologi->hujan_biasa = floatval($newContent[19]);
                        $klimatologi->sinar_matahari = floatval($newContent[20]);
                        $klimatologi->keterangan = $newContent[21];
                        $klimatologi->update();

                        Http::get($apiURLKlimatologi);

                        return ResponseFormatter::success(
                            [$sms, $klimatologi],
                            'Data SMS dan TMA Berhasil disimpan'
                        );
                    } else {
                        $data = Klimatologi::create([
                            'tanggal' => $newTanggal,

                            'termo_max_pagi' => floatval($newContent[0]),
                            'termo_max_siang' => floatval($newContent[1]),
                            'termo_max_sore' => floatval($newContent[2]),

                            'termo_min_pagi' => floatval($newContent[3]),
                            'termo_min_siang' => floatval($newContent[4]),
                            'termo_min_sore' => floatval($newContent[5]),

                            'bola_kering_pagi' => floatval($newContent[6]),
                            'bola_kering_siang' => floatval($newContent[7]),
                            'bola_kering_sore' => floatval($newContent[8]),

                            'bola_basah_pagi' => floatval($newContent[9]),
                            'bola_basah_siang' => floatval($newContent[10]),
                            'bola_basah_sore' => floatval($newContent[11]),

                            'rh' => floatval($newContent[12]),

                            'termo_apung_max' => floatval($newContent[13]),
                            'termo_apung_min' => floatval($newContent[14]),

                            'penguapan_plus' => floatval($newContent[15]),
                            'penguapan_min' => floatval($newContent[16]),

                            'anemometer_spedometer' => floatval($newContent[17]),

                            'hujan_otomatis' => floatval($newContent[18]),
                            'hujan_biasa' => floatval($newContent[19]),
                            'sinar_matahari' => floatval($newContent[20]),
                            'keterangan' => count($newContent) == 21 ? $newContent[21] : null,
                            'pos_id' => $user->pos_id,
                        ]);

                        Http::get($apiURLKlimatologi);

                        return ResponseFormatter::success(
                            [$sms, $data],
                            'Data SMS dan Klimatologi Berhasil disimpan'
                        );
                    }
                } else {
                    Http::get($apiURLSalah);
                    return ResponseFormatter::error(
                        $sms,
                        'Maaf Format SMS Salah Silahkan dicoba lagi',
                        401
                    );
                }
            } else {
                Http::get($apiURLSalah);
                return ResponseFormatter::error($sms, 'SMS Gateway Gagal', 401);
            }
        } else {

            return ResponseFormatter::success(
                $sms,
                'Data Berhasil disimpan'
            );
        }
    }
}
