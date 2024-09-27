@extends('layouts.admin')

@section('title')
    History Data Pos Curah Hujan
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
                <h5 class="mb-3 text-center">Data Yang Melewati Batas Pos Curah Hujan Bulan {{\Carbon\Carbon::parse($start_date)->format('m')}} Tahun {{\Carbon\Carbon::parse($start_date)->format('Y')}}</h5>
            @else
                <h5 class="mb-3 text-center">Data Yang Melewati Batas Pos Curah Hujan Bulan {{\Carbon\Carbon::now()->format('m')}} Tahun {{\Carbon\Carbon::now()->format('Y')}}</h5>
            @endif
            <hr class="divide">
            <table class="table table-bordered table-striped table-hover text-center">
                <thead class="bg-primary">
                    <tr>
                        <th class="text-white" rowspan="2">Tanggal</th>
                        <th class="text-white" colspan="2">Banyaknya Hujan</th>
                        <th class="text-white" rowspan="2">Keterangan</th>
                        <th class="text-white" rowspan="2">Dibuat</th>
                        <th class="text-white" rowspan="2">Diupdate</th>
                    </tr>
                    <tr>
                        <th class="text-white">Otomatis (mm)</th>
                        <th class="text-white">Biasa (mm)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($newPos as $item)
                        <tr>
                            <td>{{$item->tanggal}}</td>
                            <td>{{$item->hujan_otomatis}}</td>
                            <td>{{$item->hujan_biasa}}</td>
                            <td>{{$item->keterangan}}</td>
                            <td>{{$item->created_at}}</td>
                            <td>{{$item->updated_at}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">Tidak Ada Data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('js')
    
@endpush