<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SIH3 BWS Sumatera VI - Telementri - TMA</title>
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
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Value Calibration</th>
                        <th>Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sensorView as $item)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$item['date']}}</td>
                            <td>{{$item['time']}}</td>
                            <td>{{$item['value_calibration']}}</td>
                            <td>{{$item['datetime']}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Tidak Ada Data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>