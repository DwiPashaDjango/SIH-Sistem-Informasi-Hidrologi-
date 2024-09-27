@extends('layouts.pages')

@section('title')
    Detail Data Curah Hujans : {{$pos->nama}}
@endsection

@push('css')
@endpush    

@section('content')
    <div class="container">
       <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <h3 style="color: #000000dd">@yield('title')</h3>
                </li>
            </ol>
        </nav>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {{$error}}
                @endforeach
            </div>
        @endif

        <div class="card mt-3">
            <form action="">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="" class="mb-2">Mulai Tanggal : </label>
                                <input type="date" name="start_date" id="start_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="" class="mb-2">Mulai Tanggal : </label>
                                <input type="date" name="end_date" id="end_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <button type="submit" class="btn btn-primary w-100" style="margin-top: 30px">Filter</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item w-50 text-center" role="presentation">
                        <a class="nav-link active" id="simple-tab-0" data-bs-toggle="tab" href="#simple-tabpanel-0" role="tab" aria-controls="simple-tabpanel-0" aria-selected="true">
                            <b>Table</b>
                        </a>
                    </li>
                    <li class="nav-item w-50 text-center" role="presentation">
                        <a class="nav-link" id="simple-tab-1" data-bs-toggle="tab" href="#simple-tabpanel-1" role="tab" aria-controls="simple-tabpanel-1" aria-selected="false">
                            <b>Grafik</b>
                        </a>
                    </li>
                </ul>
                <div class="tab-content pt-5" id="tab-content">
                    <div class="tab-pane active" id="simple-tabpanel-0" role="tabpanel" aria-labelledby="simple-tab-0">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                {{-- @if (!empty($start_date) && !empty($end_date))
                                    <a href="{{route('guest.pos.crh.pdf', ['start_date' => $start_date, 'end_date' => $end_date, 'id' => $pos->id])}}" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
                                @else
                                    <a href="{{route('guest.pos.crh.pdf', ['start_date' => \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'), 'end_date' => \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'), 'id' => $pos->id])}}" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
                                @endif --}}
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <i class="fas fa-file-pdf"></i>
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover text-center" id="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th rowspan="3">No</th>
                                        <th rowspan="3">Tanggal</th>
                                        <th colspan="8">Thermometer</th>
                                        <th colspan="9">Psychrometer Standar</th>
                                        <th colspan="3">Thermometer Apung</th>
                                        <th colspan="3">Penguapan / Penakaran</th>
                                        <th colspan="1">Anemometer</th>
                                        <th colspan="2">Hujan</th>
                                        <th colspan="2">Sinar Matahari</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" style="width: 3%">Maximum</th>
                                        <th rowspan="2" style="width: 3%">RT</th>

                                        <th colspan="3" style="width: 3%">Minimum</th>
                                        <th rowspan="2" style="width: 3%">RT</th>

                                        <th colspan="3" style="width: 3%">Bola Kering</th>
                                        <th rowspan="2" style="width: 3%">RT</th>
                                        <th colspan="3" style="width: 3%">Bola Basah</th>
                                        <th rowspan="2" style="width: 3%">RT</th>
                                        <th rowspan="2" style="width: 3%">RH%</th>
                                        
                                        <th rowspan="2" style="width: 3%">Max</th>
                                        <th rowspan="2" style="width: 3%">Min</th>
                                        <th rowspan="2" style="width: 3%">RT</th>

                                        <th rowspan="2" style="width: 3%">+</th>
                                        <th rowspan="2" style="width: 3%">-</th>
                                        <th rowspan="2" style="width: 3%">Hasil</th>

                                        <th rowspan="2" style="width: 3%">Spedometer</th>

                                        <th rowspan="2" style="width: 3%">Manual</th>
                                        <th rowspan="2" style="width: 3%">Otomatis</th>

                                        <th rowspan="2" style="width: 3%">Sinar Matahari	</th>
                                        <th rowspan="2" style="width: 3%">%</th>
                                    </tr>
                                    <tr>
                                        <th style="width: 3%">07</th>
                                        <th style="width: 3%">12</th>
                                        <th style="width: 3%">17</th>

                                        <th style="width: 3%">07</th>
                                        <th style="width: 3%">12</th>
                                        <th style="width: 3%">17</th>

                                        <th style="width: 3%">07</th>
                                        <th style="width: 3%">12</th>
                                        <th style="width: 3%">17</th>

                                        <th style="width: 3%">07</th>
                                        <th style="width: 3%">12</th>
                                        <th style="width: 3%">17</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($klimatologis as $item)
                                        @php
                                            $averageTermoMax = ($item->termo_max_pagi + $item->termo_max_siang + $item->termo_max_sore) / 3;
                                            $averageTermoMin = ($item->termo_min_pagi + $item->termo_min_siang + $item->termo_min_sore) / 3;
                                            $averageBolaKering = ($item->bola_kering_pagi + $item->bola_kering_siang + $item->bola_kering_sore) / 3;
                                            $averageBolaBasah = ($item->bola_basah_pagi + $item->bola_basah_siang + $item->bola_basah_sore) / 3;
                                            $averageTermoApung = ($item->termo_apung_max + $item->termo_apung_min) / 2;
                                            $hasilPenguapan = ($item->penguapan_plus + $item->penguapan_min);
                                        @endphp
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
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
                    <div class="tab-pane" id="simple-tabpanel-1" role="tabpanel" aria-labelledby="simple-tab-1">
                        <div class="row">
                            <div class="col-lg-6">
                                <div style="width: 100%; margin: auto;">
                                    <canvas id="maxChart"></canvas>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div style="width: 100%; margin: auto;">
                                    <canvas id="minChart"></canvas>
                                </div>
                            </div>
                            <div class="col-lg-6 mt-2">
                                <div style="width: 100%; margin: auto;">
                                    <canvas id="penguapan"></canvas>
                                </div>
                            </div>
                            <div class="col-lg-6 mt-2">
                                <div style="width: 100%; margin: auto;">
                                    <canvas id="spedometer"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
<form action="{{route('guest.pos.klimatologi.generatePDF')}}" method="POST">
    @csrf
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Recaptcha</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="pos_id" id="pos_id" value="{{$pos->id}}">
            @if (!empty($start_date) && !empty($end_date))
                <input type="hidden" name="start_date" id="start_date" value="{{$start_date}}">
                <input type="hidden" name="end_date" id="end_date" value="{{$end_date}}">
            @else
                <input type="hidden" name="start_date" id="start_date" value="{{\Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')}}">
                <input type="hidden" name="end_date" id="end_date" value="{{\Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')}}">
            @endif
            {!! htmlFormSnippet() !!}
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-download"></i></button>
          </div>
        </div>
      </div>
    </div>
</form>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctxMax = document.getElementById('maxChart').getContext('2d');
            const ctxMin = document.getElementById('minChart').getContext('2d');
            const penguapan = document.getElementById('penguapan').getContext('2d');
            const spedometer = document.getElementById('spedometer').getContext('2d');

            const maxChart = new Chart(ctxMax, {
                type: 'line',
                data: {
                    labels: @json($labelsMax),
                    datasets: [
                        {
                            label: 'Pagi',
                            data: @json($maxPagi),
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2,
                            fill: false
                        },
                        {
                            label: 'Siang',
                            data: @json($maxSiang),
                            borderColor: 'rgba(255, 206, 86, 1)',
                            borderWidth: 2,
                            fill: false
                        },
                        {
                            label: 'Sore',
                            data: @json($maxSore),
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 2,
                            fill: false
                        }
                    ]
                },
                options: {
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Tanggal'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Grafik Termometer Max'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });

            const minChart = new Chart(ctxMin, {
                type: 'line',
                data: {
                    labels: @json($labelsMin),
                    datasets: [
                        {
                            label: 'Pagi',
                            data: @json($minPagi),
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2,
                            fill: false
                        },
                        {
                            label: 'Siang',
                            data: @json($minSiang),
                            borderColor: 'rgba(255, 206, 86, 1)',
                            borderWidth: 2,
                            fill: false
                        },
                        {
                            label: 'Sore',
                            data: @json($minSore),
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 2,
                            fill: false
                        }
                    ]
                },
                options: {
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Tanggal'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Grafik Termometer Min'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });

            const penguapanChart = new Chart(penguapan, {
                type: 'line',
                data: {
                    labels: @json($labelsPenguapan),
                    datasets: [
                        {
                            label: 'Penguapan Plus',
                            data: @json($maxPenguapan),
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2,
                            fill: false
                        },
                        {
                            label: 'Penguapan Min',
                            data: @json($minPenguapan),
                            borderColor: 'rgba(255, 206, 86, 1)',
                            borderWidth: 2,
                            fill: false
                        },
                    ]
                },
                options: {
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Tanggal'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Grafik Penguapan Plus/Min'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });

            const spedometerChart = new Chart(spedometer, {
                type: 'line',
                data: {
                    labels: @json($labelsAnometer),
                    datasets: [
                        {
                            label: 'Spedometer',
                            data: @json($spedometer),
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2,
                            fill: false
                        }
                    ]
                },
                options: {
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Tanggal'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Grafik Spedometer'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endpush