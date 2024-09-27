<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SIH3 BWS Sumatera VI - {{$pos->nama}} - TMA</title>
    <link rel="stylesheet" href="admin//modules/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 100%;
            margin: 0;
            padding: 0;
        }
        table {
            table-layout: fixed;
        }
        th, td {
            padding: 4px;
            font-size: 12px;
        }
    </style>

</head>
<body>
    <div class="contianer">
        <img src="{{public_path('kop.png')}}" width="500" alt="">
        <hr style="border: 1px solid #444444dd">
        <h4 style="text-align: center; margin-top: 30px;">
            KEMENTERIAN PEKERJAAN UMUM
            <br>
            DIREKTORAT SUMBER DAYA AIR
            <br>
            BALAI WILAYAH SUNGAI SUMATERA VI
            <br>
            UNIT HIDROLOGI
        </h4>

        <hr class="divide">

        <table class="table table-bordered mt-4 text-center">
            <tr>
                <td>Sungai</td>
                <td>:</td>
                <td class="text-left">{{$pos->nama}}</td>
                <td>Kabupaten</td>
                <td>:</td>
                <td class="text-left">{{$pos->regencie ? $pos->regencie->name : '-'}}</td>
            </tr>
            <tr>
                <td>Bulan</td>
                <td>:</td>
                <td class="text-left">{{\Carbon\Carbon::parse($start_date)->translatedFormat('F Y')}}</td>
                <td>Pengamat</td>
                <td>:</td>
                <td class="text-left"></td>
            </tr>
        </table>

        <hr class="divide">
        
        <table class="table table-bordered table-striped table-hover text-center mt-3" id="table" style="width: 100%">
            <thead>
                <tr>
                    <th rowspan="3">No</th>
                    <th rowspan="3">Tanggal</th>
                    <th colspan="8">Thermometer</th>
                    <th colspan="9">Psychrometer Standar</th>
                    <th colspan="3">Thermometer Apung</th>
                    <th colspan="3">Penguapan / Penakaran</th>
                    <th colspan="1">Anemometer</th>
                    <th colspan="2">Hujan</th>
                    <th colspan="2">Sinar Matahari</th>
                </tr>
                <tr>
                    <th colspan="3" style="width: 3%">Maximum</th>
                    <th rowspan="2" style="width: 3%">RT</th>

                    <th colspan="3" style="width: 3%">Minimum</th>
                    <th rowspan="2" style="width: 3%">RT</th>

                    <th colspan="3" style="width: 3%">Bola Kering</th>
                    <th rowspan="2" style="width: 3%">RT</th>
                    <th colspan="3" style="width: 3%">Bola Basah</th>
                    <th rowspan="2" style="width: 3%">RT</th>
                    <th rowspan="2" style="width: 3%">RH%</th>
                    
                    <th rowspan="2" style="width: 3%">Max</th>
                    <th rowspan="2" style="width: 3%">Min</th>
                    <th rowspan="2" style="width: 3%">RT</th>

                    <th rowspan="2" style="width: 3%">+</th>
                    <th rowspan="2" style="width: 3%">-</th>
                    <th rowspan="2" style="width: 3%">Hasil</th>

                    <th rowspan="2" style="width: 3%">Spedometer</th>

                    <th rowspan="2" style="width: 3%">Manual</th>
                    <th rowspan="2" style="width: 3%">Otomatis</th>

                    <th rowspan="2" style="width: 3%">Sinar Matahari	</th>
                    <th rowspan="2" style="width: 3%">%</th>
                </tr>
                <tr>
                    <th style="width: 3%">07</th>
                    <th style="width: 3%">12</th>
                    <th style="width: 3%">17</th>

                    <th style="width: 3%">07</th>
                    <th style="width: 3%">12</th>
                    <th style="width: 3%">17</th>

                    <th style="width: 3%">07</th>
                    <th style="width: 3%">12</th>
                    <th style="width: 3%">17</th>

                    <th style="width: 3%">07</th>
                    <th style="width: 3%">12</th>
                    <th style="width: 3%">17</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($klimatologis as $item)
                    @php
                        $averageTermoMax = ($item->termo_max_pagi + $item->termo_max_siang + $item->termo_max_sore) / 3;
                        $averageTermoMin = ($item->termo_min_pagi + $item->termo_min_siang + $item->termo_min_sore) / 3;
                        $averageBolaKering = ($item->bola_kering_pagi + $item->bola_kering_siang + $item->bola_kering_sore) / 3;
                        $averageBolaBasah = ($item->bola_basah_pagi + $item->bola_basah_siang + $item->bola_basah_sore) / 3;
                        $averageTermoApung = ($item->termo_apung_max + $item->termo_apung_min) / 2;
                        $hasilPenguapan = ($item->penguapan_plus + $item->penguapan_min);
                    @endphp
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->tanggal}}</td>
                        <td>{{$item->termo_max_pagi}}</td>
                        <td>{{$item->termo_max_siang}}</td>
                        <td>{{$item->termo_max_sore}}</td>
                        <td>{{number_format($averageTermoMax, 2)}}</td>
                        <td>{{$item->termo_min_pagi}}</td>
                        <td>{{$item->termo_min_siang}}</td>
                        <td>{{$item->termo_min_sore}}</td>
                        <td>{{number_format($averageTermoMin, 2)}}</td>
                        <td>{{$item->bola_kering_pagi}}</td>
                        <td>{{$item->bola_kering_siang}}</td>
                        <td>{{$item->bola_kering_sore}}</td>
                        <td>{{number_format($averageBolaKering, 2)}}</td>
                        <td>{{$item->bola_basah_pagi}}</td>
                        <td>{{$item->bola_basah_siang}}</td>
                        <td>{{$item->bola_basah_sore}}</td>
                        <td>{{number_format($averageBolaBasah, 2)}}</td>
                        <td>{{$item->rh}}</td>
                        <td>{{$item->termo_apung_max}}</td>
                        <td>{{$item->termo_apung_min}}</td>
                        <td>{{number_format($averageTermoApung, 2)}}</td>
                        <td>{{$item->penguapan_plus}}</td>
                        <td>{{$item->penguapan_min}}</td>
                        <td>{{number_format($hasilPenguapan)}}</td>
                        <td>{{ number_format($item->anemometer_spedometer) }}</td>
                        <td>{{$item->hujan_otomatis}}</td>
                        <td>{{$item->hujan_biasa}}</td>
                        <td>{{$item->sinar_matahari}}</td>
                        <td>{{number_format($item->sinar_matahari, 1)}}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100">Tidak Ada Data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="display: flex; flex-direction: column; align-items: flex-end; text-align: right;" class="mt-3">
            <p>Jambi, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
            <p>Di Periksa Oleh</p>
            <br>
            <br>
            <br>
            <p>.............................</p>
        </div>

    </div>
</body>
</html>