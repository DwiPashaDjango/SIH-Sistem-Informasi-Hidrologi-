@extends('layouts.admin')

@section('title')
    Hasil Uji Kualitas Air
@endsection

@push('css')
    
@endpush    

@section('content')
@if ($qualityWater->total >= 0 && $qualityWater->total <= 1.0)
    <div class="alert alert-primary">
        Status Mutu Air Adalah :<b>Kondisi Baik</b> Dengan Total : {{$qualityWater->total}}
    </div>
@elseif ($qualityWater->total > 1.0 && $qualityWater->total <= 5.0)
    <div class="alert alert-info">
        Status Mutu Air Adalah :<b>Cemar Ringan</b> Dengan Total : {{$qualityWater->total}}
    </div>
@elseif ($qualityWater->total > 5.0 && $qualityWater->total <= 10.0)
    <div class="alert alert-warning">
        Status Mutu Air Adalah :<b>Cemar Sedang</b> Dengan Total : {{$qualityWater->total}}
    </div>
@elseif ($qualityWater->total > 10.0)
    <div class="alert alert-danger">
        Status Mutu Air Adalah :<b>Cemar Berat</b> Dengan Total : {{$qualityWater->total}}
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label for="">Semester</label>
                    <input type="text" name="" id="" class="form-control" readonly value="Semster {{$qualityWater->semester}}">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <label for="">Tahun</label>
                    <input type="text" name="" id="" class="form-control" readonly value="{{$qualityWater->tahun}}">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        <a href="{{route('water.quality.generatePDFQualityWater', ['id' => $qualityWater->id])}}" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
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
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="pH"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="-"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->ph}}" class="form-control text-center" required></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">2</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Suhu Air"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="C"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->suhu}}" class="form-control text-center" required></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">3</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Zat Terlarut"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->zat}}" class="form-control text-center" required></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">4</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Orp"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Mvorp"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->orp}}"class="form-control text-center" required></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">5</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Conductivity"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="us/cm"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->conductivity}}" class="form-control text-center" required></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">6</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Resistivity"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="cm"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->resistivity}}" class="form-control text-center" required></td>
                </tr>

                <!-- Bagian Kimia -->
                <tr>
                    <td></td>
                    <td colspan="3" class="text-start"><strong>Kimia</strong></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">1</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Oksigen Terlarut/DO"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->oksigen}}" class="form-control text-center" required></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">2</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Cod"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->cod}}" class="form-control text-center" required></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">3</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Khlorida"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->khlorida}}" class="form-control text-center" required></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">4</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Nitrit"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->nitrit}}" class="form-control text-center" required></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">5</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Nitrat"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->nitrat}}" class="form-control text-center" required></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">6</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Sulfat"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->sulfat}}" class="form-control text-center" required></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">7</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Phospat"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->phospat}}" class="form-control text-center" required></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">8</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Amonia"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->amonia}}" class="form-control text-center" required></td>
                </tr>

                <!-- Bagian Logam -->
                <tr>
                    <td></td>
                    <td colspan="3" class="text-start"><strong>Logam</strong></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">1</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Tembaga"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->tembaga}}" class="form-control text-center" required></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">2</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Mangan"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->mangan}}" class="form-control text-center" required></td>
                </tr>
                <tr class="data-row">
                    <td class="text-center">3</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Chrom"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                    <td class="text-center"><input type="text" readonly value="{{$qualityWater->chrom}}" class="form-control text-center" required></td>
                </tr>
                <tr class="">
                    <td class="text-center">4</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Logam"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                    <td class="text-center"><input type="text" name="logam" class="form-control text-center" value="{{$qualityWater->logam}}" readonly></td>
                    <td class="text-center">-</td>
                </tr>
                <tr class="">
                    <td class="text-center">5</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Seng"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                    <td class="text-center"><input type="text" name="seng" class="form-control text-center" value="{{$qualityWater->seng}}" readonly></td>
                    <td class="text-center">-</td>
                </tr>
                <tr class="">
                    <td class="text-center">6</td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="Besi"></td>
                    <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                    <td class="text-center"><input type="text" name="besi" class="form-control text-center" value="{{$qualityWater->besi}}" readonly></td>
                    <td class="text-center">-</td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="3" class="text-start"><strong>Lainnya</strong></td>
                </tr>
                @foreach ($qualityWater->detail as $dtl)
                    <tr class="data-row">
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="{{$dtl->parameter}}"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="{{$dtl->satuan}}"></td>
                        <td class="text-center"><input type="text" readonly value="{{$dtl->hasil}}" class="form-control text-center" required></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-primary">
                <tr>
                    <th colspan="3" class="text-center text-white">Total</th>
                    <th><input type="text" readonly value="{{$qualityWater->total}}" class="form-control text-center" required></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

@push('js')

@endpush