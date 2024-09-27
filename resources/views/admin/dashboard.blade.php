@extends('layouts.admin')

@section('title')
    Dashboard
@endsection

@push('css')
    
@endpush

@section('content')
    @if(auth()->user()->hasAnyRole(['Admin', 'Pimpinan']))
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="far fa-user"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Users</h4>
                        </div>
                        <div class="card-body">
                            {{$users}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="far fas fa-building"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Tinggi Muka Air</h4>
                        </div>
                        <div class="card-body">
                            {{$tma}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Curah Hujan</h4>
                        </div>
                        <div class="card-body">
                            {{$crh}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Klimatologi</h4>
                        </div>
                        <div class="card-body">
                            {{$klimatologi}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if(auth()->user()->hasAnyRole(['User']))
        Hallo
    @endif
@endsection

@push('js')
    
@endpush