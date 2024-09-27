<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SIH3 BWS Sumatera VI - {{$pos->nama}} - Curah Hujan</title>
    <link rel="stylesheet" href="admin//modules/bootstrap/css/bootstrap.min.css">
    {{-- <link rel="stylesheet" href="admin/css/style.css"> --}}
    <link rel="stylesheet" href="admin/css/components.css">
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
        <h4 style="text-align: center;">PEMERIKSAAN CURAH HUJAN</h4>

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
                    <th rowspan="2">No</th>
                    <th rowspan="2">Tanggal</th>
                    <th colspan="2">Banyaknya Hujan</th>
                    <th rowspan="2">Keterangan</th>
                </tr>
                <tr>
                    <th>Otomatis (mm)</th>
                    <th>Biasa (mm)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($crhs as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->tanggal}}</td>
                        <td>{{$item->hujan_otomatis}}</td>
                        <td>{{$item->hujan_biasa}}</td>
                        <td>{{$item->keterangan}}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Tidak Ada Data</td>
                    </tr>
                @endforelse
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