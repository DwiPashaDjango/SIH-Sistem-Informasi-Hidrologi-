@extends('layouts.pages')

@section('title')
    Detail Data Telemetri Curah Hujan
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
                                <input type="datetime-local" name="start" id="start" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="" class="mb-2">Mulai Tanggal : </label>
                                <input type="datetime-local" name="end" id="end" class="form-control">
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
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <i class="fas fa-file-pdf"></i>
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Jam</th>
                                        <th>Value Calibration</th>
                                        <th>Dibuat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($sensorView as $item)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$item['date']}}</td>
                                            <td>{{$item['time']}}</td>
                                            <td>{{$item['value_calibration']}}</td>
                                            <td>{{$item['datetime']}}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">Tidak Ada Data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="simple-tabpanel-1" role="tabpanel" aria-labelledby="simple-tab-1">
                        <div style="width: 100%; margin: auto;">
                            <canvas id="chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
<form action="{{route('telementri.generatePDF')}}" method="POST">
    @csrf
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Recaptcha</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="sensor_company_id" id="sensor_company_id" value="{{$sensor_company_id}}">
            @if (!empty($start) && !empty($end))
                <input type="hidden" name="start" id="start" value="{{$start}}">
                <input type="hidden" name="end" id="end" value="{{$end}}">
            @else
                <input type="hidden" name="start" id="start" value="{{\Carbon\Carbon::now()->format('Y-m-d H:i:s')}}">
                <input type="hidden" name="end" id="end" value="{{\Carbon\Carbon::now()->format('Y-m-d H:i:s')}}">
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
    {!! ReCaptcha::htmlScriptTagJsApi() !!}
    <script>
        var labels = @json($labels);
        var values = @json($values);

        var ctx = document.getElementById('chart').getContext('2d');
        var sensorChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Value Calibration',
                    data: values,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Tanggal & Jam'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        display: true,
                        title: {
                            display: true,
                            text: 'Value Calibration'
                        }
                    }
                }
            }
        });
    </script>
@endpush