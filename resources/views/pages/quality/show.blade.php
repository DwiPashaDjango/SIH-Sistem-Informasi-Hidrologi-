@extends('layouts.pages')

@section('title')
    Detail Data Uji Kualitas Air : {{$pos->nama}}
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
                                <label for="" class="mb-2">Semester : </label>
                                <select name="semester" id="semester" class="form-control">
                                    <option value="">- Pilih -</option>
                                    <option value="1">Semester 1</option>
                                    <option value="2">Semester 2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="" class="mb-2">Tahun : </label>
                                <select name="year" id="year" class="form-control">
                                    <option value="">- Pilih -</option>
                                    @php
                                        $currentYear = date("Y");
                                        for ($i = 0; $i < 5; $i++) {
                                            $year = $currentYear - $i;
                                            echo "<option value='{$year}'>{$year}</option>";
                                        }
                                    @endphp
                                </select>
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
                                    <a href="{{route('pos.generatePDF', ['start_date' => $start_date, 'end_date' => $end_date, 'id' => $pos->id])}}" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
                                @else
                                    <a href="{{route('pos.generatePDF', ['start_date' => \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'), 'end_date' => \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'), 'id' => $pos->id])}}" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
                                @endif --}}
                                {{-- <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <i class="fas fa-file-pdf"></i>
                                </button> --}}
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Semester</th>
                                        <th>Tanggal</th>
                                        <th>Hasil Uji</th>
                                        <th>Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($waterQualitys as $item)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>Semester {{$item->semester}}</td>
                                            <td>{{\Carbon\Carbon::parse($item->created_at)->format('Y-m-d')}}</td>
                                            <td>{{$item->total}}</td>
                                            <td>
                                                @if ($item->total > 0 && $item->total <= 1.0)
                                                    Kondisi Baik
                                                @elseif($item->total >= 1.0 && $item->total <= 5.0)
                                                    Cemar Ringan
                                                @elseif($item->total >= 5.0 && $item->total <= 10.0)
                                                    Cemar Sedang
                                                @elseif($item->total > 10.0)
                                                    Cemar Berat
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{route('guest.pos.quality.generatePDFQualityWater', ['id' => $item->id])}}" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9">Tidak Ada Data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{$waterQualitys->links()}}
                        </div>
                    </div>
                    <div class="tab-pane" id="simple-tabpanel-1" role="tabpanel" aria-labelledby="simple-tab-1">
                        <div style="width: 100%; margin: auto;">
                            <canvas id="tmaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
<form action="{{route('pos.generatePDF')}}" method="POST">
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
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.0.0"></script>
    {!! ReCaptcha::htmlScriptTagJsApi() !!}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('tmaChart').getContext('2d');
            const tmaChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [
                        {
                            label: 'Hasil Uji Kualitas Air',
                            data: @json($total),
                            borderColor: 'rgba(75, 192, 192, 1)',
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
                                text: 'Total Kualitas Air'
                            },
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        annotation: {
                            annotations: {
                                kondisiBaik: {
                                    type: 'line',
                                    yMin: 1, 
                                    yMax: 1,
                                    borderColor: 'rgba(55, 102, 236, 0.8)',
                                    borderWidth: 2,
                                    label: {
                                        enabled: true,
                                        content: 'Kondisi Baik',
                                        position: 'end'
                                    }
                                },
                                cemarRingan: {
                                    type: 'line',
                                    yMin: 5, 
                                    yMax: 5,
                                    borderColor: 'rgba(255, 215, 1, 0.8)',
                                    borderWidth: 2,
                                    label: {
                                        enabled: true,
                                        content: 'Cemar Ringan',
                                        position: 'end'
                                    }
                                },
                                cemarSedang: {
                                    type: 'line',
                                    yMin: 10, 
                                    yMax: 10,
                                    borderColor: 'rgba(251, 139, 0, 0.8)',
                                    borderWidth: 2,
                                    label: {
                                        enabled: true,
                                        content: 'Cemar Sedang',
                                        position: 'end'
                                    }
                                },
                                cemarBerat: {
                                    type: 'line',
                                    yMin: 15,
                                    yMax: 15,
                                    borderColor: 'rgba(251, 0, 0, 0.8)',
                                    borderWidth: 2,
                                    label: {
                                        enabled: true,
                                        content: 'Cemar Berat',
                                        position: 'end'
                                    }
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush