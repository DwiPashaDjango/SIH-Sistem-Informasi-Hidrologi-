@extends('layouts.admin')

@section('title')
    History Data Pos Klimatologi
@endsection

@push('css')
    
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Mulai Tanggal :</label>
                            <input type="date" name="start_date" id="start_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Sampai Tanggal :</label>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <button type="submit" class="btn btn-primary w-100" style="margin-top: 30px;">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            @if (!empty($start_date) && !empty($end_date))
                <h5 class="mb-3 text-center">Data Yang Melewati Batas Pos Klimatologi Bulan {{\Carbon\Carbon::parse($start_date)->format('m')}} Tahun {{\Carbon\Carbon::parse($start_date)->format('Y')}}</h5>
            @else
                <h5 class="mb-3 text-center">Data Yang Melewati Batas Pos Klimatologi Bulan {{\Carbon\Carbon::now()->format('m')}} Tahun {{\Carbon\Carbon::now()->format('Y')}}</h5>
            @endif
            <hr class="divide">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover text-center" id="table" style="width: 100%">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-white" rowspan="3">Tanggal</th>
                            <th class="text-white" colspan="8">Thermometer</th>
                            <th class="text-white" colspan="9">Psychrometer Standar</th>
                            <th class="text-white" colspan="3">Thermometer Apung</th>
                            <th class="text-white" colspan="3">Penguapan / Penakaran</th>
                            <th class="text-white" colspan="1">Anemometer</th>
                            <th class="text-white" colspan="2">Hujan</th>
                            <th class="text-white" colspan="2">Sinar Matahari</th>
                        </tr>
                        <tr>
                            <th class="text-white" colspan="3" style="width: 3%">Maximum</th>
                            <th class="text-white" rowspan="2" style="width: 3%">RT</th>

                            <th class="text-white" colspan="3" style="width: 3%">Minimum</th>
                            <th class="text-white" rowspan="2" style="width: 3%">RT</th>

                            <th class="text-white" colspan="3" style="width: 3%">Bola Kering</th>
                            <th class="text-white" rowspan="2" style="width: 3%">RT</th>
                            <th class="text-white" colspan="3" style="width: 3%">Bola Basah</th>
                            <th class="text-white" rowspan="2" style="width: 3%">RT</th>
                            <th class="text-white" rowspan="2" style="width: 3%">RH%</th>
                            
                            <th class="text-white" rowspan="2" style="width: 3%">Max</th>
                            <th class="text-white" rowspan="2" style="width: 3%">Min</th>
                            <th class="text-white" rowspan="2" style="width: 3%">RT</th>

                            <th class="text-white" rowspan="2" style="width: 3%">+</th>
                            <th class="text-white" rowspan="2" style="width: 3%">-</th>
                            <th class="text-white" rowspan="2" style="width: 3%">Hasil</th>

                            <th class="text-white" rowspan="2" style="width: 3%">Spedometer</th>

                            <th class="text-white" rowspan="2" style="width: 3%">Manual</th>
                            <th class="text-white" rowspan="2" style="width: 3%">Otomatis</th>

                            <th class="text-white" rowspan="2" style="width: 3%">Sinar Matahari	</th>
                            <th class="text-white" rowspan="2" style="width: 3%">%</th>
                        </tr>
                        <tr>
                            <th class="text-white" style="width: 3%">07</th>
                            <th class="text-white" style="width: 3%">12</th>
                            <th class="text-white" style="width: 3%">17</th>

                            <th class="text-white" style="width: 3%">07</th>
                            <th class="text-white" style="width: 3%">12</th>
                            <th class="text-white" style="width: 3%">17</th>

                            <th class="text-white" style="width: 3%">07</th>
                            <th class="text-white" style="width: 3%">12</th>
                            <th class="text-white" style="width: 3%">17</th>

                            <th class="text-white" style="width: 3%">07</th>
                            <th class="text-white" style="width: 3%">12</th>
                            <th class="text-white" style="width: 3%">17</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($newPos as $item)
                            @php
                                $averageTermoMax = ($item->termo_max_pagi + $item->termo_max_siang + $item->termo_max_sore) / 3;
                                $averageTermoMin = ($item->termo_min_pagi + $item->termo_min_siang + $item->termo_min_sore) / 3;
                                $averageBolaKering = ($item->bola_kering_pagi + $item->bola_kering_siang + $item->bola_kering_sore) / 3;
                                $averageBolaBasah = ($item->bola_basah_pagi + $item->bola_basah_siang + $item->bola_basah_sore) / 3;
                                $averageTermoApung = ($item->termo_apung_max + $item->termo_apung_min) / 2;
                                $hasilPenguapan = ($item->penguapan_plus + $item->penguapan_min);
                            @endphp
                            <tr>
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
            </div>
        </div>
    </div>
@endsection

@push('js')
    
@endpush