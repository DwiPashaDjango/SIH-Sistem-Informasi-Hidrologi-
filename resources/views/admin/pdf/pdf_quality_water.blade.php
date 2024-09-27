<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Uji Kualitas Air Pos - {{$qualityWater->pos->nama}}</title>
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
        <h4 style="text-align: center;">UJI KUALITAS AIR POS {{$qualityWater->pos->nama}}</h4>
        
        <hr class="divide">

        @if ($qualityWater->total >= 0 && $qualityWater->total <= 1.0)
            <div class="alert alert-primary">
                Status Mutu Air Adalah :<b> Kondisi Baik</b> Dengan Total : {{$qualityWater->total}}
            </div>
        @elseif ($qualityWater->total > 1.0 && $qualityWater->total <= 5.0)
            <div class="alert alert-info">
                Status Mutu Air Adalah :<b> Cemar Ringan</b> Dengan Total : {{$qualityWater->total}}
            </div>
        @elseif ($qualityWater->total > 5.0 && $qualityWater->total <= 10.0)
            <div class="alert alert-warning">
                Status Mutu Air Adalah :<b> Cemar Sedang</b> Dengan Total : {{$qualityWater->total}}
            </div>
        @elseif ($qualityWater->total > 10.0)
            <div class="alert alert-danger">
                Status Mutu Air Adalah :<b> Cemar Berat</b> Dengan Total : {{$qualityWater->total}}
            </div>
        @endif

        <table class="table table-bordered mt-4 text-center">
            <tr>
                <td>Semester</td>
                <td>:</td>
                <td class="text-left">{{$qualityWater->semester}}</td>
                <td>Tahun</td>
                <td>:</td>
                <td class="text-left">{{$qualityWater->tahun}}</td>
            </tr>
        </table>

        <table class="table table-bordered mt-3">
            <thead class="bg-primary">
                <tr>
                    <th class="text-white text-center">No</th>
                    <th class="text-white text-center">Parameter</th>
                    <th class="text-white text-center">Satuan</th>
                    <th class="text-white text-center">Hasil Uji</th>
                </tr>
            </thead>
            <tbody>
                <!-- Bagian Fisika -->
                <tr>
                    <td></td>
                    <td colspan="3" class="text-start"><strong>Fisika</strong></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">1</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="pH" style="width: 90%"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="-" style="width: 80%"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->ph}}" class="form-control text-center" required style="width: 80%"></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">2</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Suhu Air" style="width: 90%"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="C" style="width: 80%"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->suhu}}" class="form-control text-center" required style="width: 80%"></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">3</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Zat Terlarut" style="width: 90%"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l" style="width: 80%"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->zat}}" class="form-control text-center" required style="width: 80%"></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">4</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Orp" style="width: 90%"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Mvorp" style="width: 80%"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->orp}}"class="form-control text-center" required style="width: 80%"></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">5</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Conductivity" style="width: 90%"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="us/cm" style="width: 80%"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->conductivity}}" class="form-control text-center" required style="width: 80%"></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">6</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Resistivity" style="width: 90%"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="cm" style="width: 80%"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->resistivity}}" class="form-control text-center" required style="width: 80%"></td>
                </tr>

                <!-- Bagian Kimia -->
                <tr>
                    <td></td>
                    <td colspan="3" class="text-start"><strong>Kimia</strong></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">1</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Oksigen Terlarut/DO" style="width: 90%"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l" style="width: 80%"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->oksigen}}" class="form-control text-center" required style="width: 80%"></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">2</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Cod" style="width: 90%"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l" style="width: 80%"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->cod}}" class="form-control text-center" required style="width: 80%"></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">3</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Khlorida" style="width: 90%"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l" style="width: 80%"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->khlorida}}" class="form-control text-center" required style="width: 80%"></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">4</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Nitrit" style="width: 90%"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l" style="width: 80%"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->nitrit}}" class="form-control text-center" required style="width: 80%"></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">5</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Nitrat" style="width: 90%"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l" style="width: 80%"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->nitrat}}" class="form-control text-center" required style="width: 80%"></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">6</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Sulfat" style="width: 90%"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l" style="width: 80%"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->sulfat}}" class="form-control text-center" required style="width: 80%"></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">7</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Phospat" style="width: 90%"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l" style="width: 80%"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->phospat}}" class="form-control text-center" required style="width: 80%"></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">8</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Amonia" style="width: 90%"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l" style="width: 80%"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->amonia}}" class="form-control text-center" required style="width: 80%"></td>
                </tr>

                <!-- Bagian Logam -->
                <tr>
                    <td></td>
                    <td colspan="3" class="text-start"><strong>Logam</strong></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">1</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Tembaga" style="width: 90%"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l" style="width: 80%"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->tembaga}}" class="form-control text-center" required style="width: 80%"></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">2</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Mangan" style="width: 90%"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l" style="width: 80%"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->mangan}}" class="form-control text-center" required style="width: 80%"></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">3</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Chrom" style="width: 90%"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l" style="width: 80%"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->chrom}}" class="form-control text-center" required style="width: 80%"></td>
                </tr>
                <tr class="">
                    <td class="text-center">4</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Seng" style="width: 90%"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l" style="width: 80%"></td>
                    <td class="text-center"><input type="text" name="seng" class="form-control text-center" value="{{$qualityWater->seng}}" readonly style="width: 80%"></td>
                </tr>
                <tr class="">
                    <td class="text-center">5</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Besi" style="width: 90%"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l" style="width: 80%"></td>
                    <td class="text-center"><input type="text" name="besi" class="form-control text-center" value="{{$qualityWater->besi}}" readonly style="width: 80%"></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="3" class="text-start"><strong>Lainnya</strong></td>
                </tr>
                @foreach ($qualityWater->detail as $dtl)
                    <tr class="data-row">
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="{{$dtl->parameter}}" style="width: 90%"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="{{$dtl->satuan}}" style="width: 80%"></td>
                        <td class="text-center"><input type="text" readonly value="{{$dtl->hasil}}" class="form-control text-center" required style="width: 80%"></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-primary">
                <tr>
                    <th colspan="3" class="text-center text-white">Total</th>
                    <th><input type="text" readonly value="{{$qualityWater->total}}" class="form-control text-center" required style="width: 80%"></th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>