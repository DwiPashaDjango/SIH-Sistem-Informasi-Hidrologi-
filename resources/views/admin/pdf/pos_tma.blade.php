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
            font-family: Arial;
        }
    </style>
</head>
<body>
    <div class="contianer">
        <img src="{{public_path('kop.png')}}" width="500" alt="">
        <hr style="border: 1px solid #444444dd">
        <h4 style="text-align: center; margin-top: 30px;">UNIT HIDROLOGI</h4>
        <h4 style="text-align: center;">PEMERIKSAAN TINGGI MUKA AIR</h4>

        <table class="table table-bordered mt-4 text-center">
            <tr>
                <td>Sungai</td>
                <td>:</td>
                <td class="text-left">{{$pos->nama}}</td>
                <td>Stasiun</td>
                <td>:</td>
                <td class="text-left">{{$pos->lokasi}}</td>
            </tr>
            <tr>
                <td>Bulan</td>
                <td>:</td>
                <td class="text-left">{{\Carbon\Carbon::parse($start_date)->translatedFormat('F')}}</td>
                <td>Tahun</td>
                <td>:</td>
                <td class="text-left">{{\Carbon\Carbon::parse($start_date)->translatedFormat('Y')}}</td>
            </tr>
        </table>
        <table class="table table-bordered table-striped text-center mt-3">
            <thead>
                <tr>
                    <th rowspan="2" style="vertical-align: middle">No</th>
                    <th rowspan="2" style="vertical-align: middle">Tanggal</th>
                    <th colspan="3">Waktu</th>
                    <th rowspan="2" style="vertical-align: middle">Harian <br> (Rata-rata)</th>
                    <th rowspan="2" style="vertical-align: middle">Keterangan</th>
                </tr>
                <tr>
                    <th>Pagi</th>
                    <th>Siang</th>
                    <th>Sore</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tmas as $item)
                    @php
                        $averagePerRow = ($item->pagi + $item->siang + $item->sore) / 3;
                    @endphp
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{\Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y')}}</td>
                        <td>{{$item->pagi}} cm</td>
                        <td>{{$item->siang}} cm</td>
                        <td>{{$item->sore}} cm</td>
                        <td>{{number_format($averagePerRow, 2)}} cm</td>
                        <td>{{$item->keterangan}}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">Tidak Ada Data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="display: flex; flex-direction: column; align-items: flex-end; text-align: right;" class="mt-3">
            <p>Jambi, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
            <p>Petugas Pengamat</p>
            <br>
            <br>
            <br>
            <p>.............................</p>
        </div>

    </div>
</body>
</html>