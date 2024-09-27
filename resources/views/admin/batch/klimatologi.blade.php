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
            <p><strong>Klimatologi</strong></p>
            <p>
                Silahkan download template yang sudah di sediakan:
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <a href="{{asset('batch/batch_input_klimatologi.xlsx')}}" class="btn btn-outline-success"><i class="fas fa-file-excel"></i> Download Template</a>
        </div>
        <div class="card-body">
            <form action="{{route('batch.importKlima')}}" method="POST" enctype="multipart/form-data">
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