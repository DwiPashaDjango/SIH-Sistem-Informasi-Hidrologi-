@extends('layouts.admin')

@section('title')
    Batch Input (Input Banyak Data)
@endsection

@push('css')
    <link rel="stylesheet" href="{{asset('admin')}}/modules/select2/dist/css/select2.min.css">
@endpush

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session()->get('message'))
        <div class="alert alert-primary">
            {{session()->get('message')}}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h4>Panduan Penggunaan Input Banyak Data</h4>
        </div>
        <div class="card-body">
            <p><strong>Tinggi Muka Air</strong></p>
            <p>
                Gunakan file dengan ekstensi .xlsx. Harap isi dengan contoh file dengan kolom sebagai berikut, atau download template yang sudah di sediakan:
            </p>
            <div class="table-responsive mt-3">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>tanggal</th>
                            <th>pagi</th>
                            <th>siang</th>
                            <th>sore</th>
                            <th>keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                Tanggal dengan format "DD/MM/YYYY" atau "02/08/2022" wajib	
                            </td>
                            <td>
                                Bilangan angka wajib	
                            </td>
                            <td>
                                Bilangan angka wajib	
                            </td>
                            <td>
                                Bilangan angka wajib	
                            </td>
                            <td>
                                Kalimat Keterangan opsional	
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <a href="{{asset('batch/batch_input_tmas.xlsx')}}" class="btn btn-outline-success"><i class="fas fa-file-excel"></i> Download Template</a>
        </div>
        <div class="card-body">
            <form action="{{route('batch.importTMA')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                    <label for="">Pos Yang Dituju</label>
                    <select name="pos_id" id="pos_id" class="form-control select2">
                        <option value="">- Pilih -</option>
                        @foreach ($pos as $ps)
                            <option value="{{$ps->id}}">{{$ps->nama}} - ({{$ps->id}})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="">Tempalte Yang Sudah Di Isi Datanya</label>
                    <input type="file" name="file" id="file" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary w-100">Import Data</button>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{asset('admin')}}/modules/select2/dist/js/select2.full.min.js"></script>
@endpush