@extends('layouts.admin')

@section('title')
    Tambah Data Subdas
@endsection

@push('css')
    
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{route('subdas')}}" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{route('subdas.store')}}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="" class="mb-2">Nama Subdas</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <span class="invalid-feedback">
                            {{$message}}
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100">Tambah</button>
            </form>
        </div>
    </div>
@endsection

@push('js')
    
@endpush