@extends('layouts.admin')

@section('title')
    Tambah Data Curah Hujan - ({{$pos->nama}})
@endsection

@push('css')
    
@endpush

@section('content')
    @if (session()->has('message'))
        <div class="alert alert-primary">
            {{session()->get('message')}}
        </div>
    @endif
    @if (session()->has('warning'))
        <div class="alert alert-warning">
            {{session()->get('warning')}}
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <a href="{{route('pos.crh.show', ['id' => $pos->id])}}" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{route('pos.crh.storeCRH')}}" method="POST">
                @csrf
                <input type="hidden" name="pos_id" id="pos_id" value="{{$pos->id}}">
                <div class="form-group mb-3">
                    <label for="" class="mb-2">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}">
                    @error('tanggal')
                        <span class="invalid-feedback">
                            {{$message}}
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="" class="mb-2">Jenis Alat</label>
                    <select name="jenis" id="jenis" class="form-control @error('jenis') is-invalid @enderror">
                        <option value="">- Pilih -</option>
                        <option value="biasa">Hujan Biasa</option>
                        <option value="otomatis">Hujan Otomatis</option>
                    </select>
                    @error('jenis')
                        <span class="invalid-feedback">
                            {{$message}}
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="" class="mb-2">Banyak Hujan (mm)</label>
                    <input type="text" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror">
                    @error('jumlah')
                        <span class="invalid-feedback">
                            {{$message}}
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="" class="mb-2">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" cols="30" rows="10"></textarea>
                    @error('keterangan')
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