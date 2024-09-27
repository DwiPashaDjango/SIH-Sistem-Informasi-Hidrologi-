@extends('layouts.admin')

@section('title')
    Edit Tinggi Muka Air - {{$tma->pos->nama}}
@endsection

@push('css')
    
@endpush

@section('content')
    @if (session()->has('message'))
        <div class="alert alert-primary">
            {{session()->get('message')}}
        </div>
    @endif
    <div class="card mb-3">
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover text-center">
                <thead class="bg-primary">
                    <tr>
                        <th class="text-white" rowspan="2">No</th>
                        <th class="text-white" rowspan="2">Tanggal</th>
                        <th class="text-white" colspan="3">Waktu</th>
                        <th class="text-white" rowspan="2">Harian <br> Rata Rata </th>
                        <th class="text-white" rowspan="2">Keterangan</th>
                        <th class="text-white" rowspan="2">Dibuat</th>
                        <th class="text-white" rowspan="2">Diupdate</th>
                    </tr>
                    <tr>
                        <th class="text-white">Pagi</th>
                        <th class="text-white">Siang</th>
                        <th class="text-white">Sore</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $averagePerRow = ($tma->pagi + $tma->siang + $tma->sore) / 3;
                    @endphp
                    <tr>
                        <td>1</td>
                        <td>{{$tma->tanggal}}</td>
                        <td>{{$tma->pagi}} cm</td>
                        <td>{{$tma->siang}} cm</td>
                        <td>{{$tma->sore}} cm</td>
                        <td>{{number_format($averagePerRow, 2)}} cm</td>
                        <td>{{$tma->keterangan}}</td>
                        <td>{{$tma->created_at}}</td>
                        <td>{{$tma->updated_at}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <a href="{{route('pos.tma.show', ['id' => $tma->pos->id])}}" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{route('pos.tma.updateTMA', ['id' => $tma->id])}}" method="POST">
                @csrf
                @method("PUT")
                <div class="form-group mb-3">
                    <label for="" class="mb-2">Tanggal</label>
                    <input type="date" value="{{$tma->tanggal}}" name="" id="" class="form-control" disabled>
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