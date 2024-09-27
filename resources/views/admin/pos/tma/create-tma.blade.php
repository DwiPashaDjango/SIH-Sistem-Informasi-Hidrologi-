@extends('layouts.admin')

@section('title')
    Input Tinggi Muka Air - {{$pos->nama}}
@endsection

@push('css')
    
@endpush

@section('content')
     @if (session()->has('message'))
        <div class="alert alert-primary">
            <b>{{session()->get('message')}}</b>
        </div>
    @endif
    @if (session()->has('warning'))
        <div class="alert alert-warning">
            <b>{{session()->get('warning')}}</b>
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <a href="{{route('pos.tma.show', ['id' => $pos->id])}}" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{route('pos.tma.storeTMA')}}" method="POST">
                @csrf
                <input type="hidden" name="pos_id" id="pos_id" value="{{$pos->id}}">
                <div class="form-group mb-3">
                    <label for="" class="mb-2">Tanggal</label>
                    <input type="date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror">
                    @error('tanggal')
                        <span class="invalid-feedback">
                            {{$message}}
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="" class="mb-2">Jam</label>
                    <select name="jam" id="jam" class="form-control @error('jam') is-invalid @enderror">
                        <option value="">- Pilih -</option>
                        <option value="07.00">07.00</option>
                        <option value="12.00">12.00</option>
                        <option value="17.00">17.00</option>
                    </select>
                    @error('jam')
                        <span class="invalid-feedback">
                            {{$message}}
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="" class="mb-2">TMA (cm)</label>
                    <input type="text" name="tma" id="tma" class="form-control @error('tma') is-invalid @enderror">
                    @error('tma')
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