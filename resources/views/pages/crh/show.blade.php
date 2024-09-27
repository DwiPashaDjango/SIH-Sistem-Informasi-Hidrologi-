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
                            <table class="table table-bordered table-striped table-hover text-center">
                                <thead>
                                    <tr>
                                        <th rowspan="2">No</th>
                                        <th rowspan="2">Tanggal</th>
                                        <th colspan="2">Banyaknya Hujan</th>
                                        <th rowspan="2">Keterangan</th>
                                        <th rowspan="2">Dibuat</th>
                                        <th rowspan="2">Diupdate</th>
                                    </tr>
                                    <tr>
                                        <th>Hujan Biasa (mm)</th>
                                        <th>Hujan Otomatis (mm)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($crhs as $item)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$item->tanggal}}</td>
                                            <td>{{$item->hujan_biasa}}</td>
                                            <td>{{$item->hujan_otomatis}}</td>
                                            <td>{{$item->keterangan}}</td>
                                            <td>{{$item->created_at}}</td>
                                            <td>{{$item->updated_at}}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9">Tidak Ada Data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{$crhs->links()}}
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
<form action="{{route('guest.pos.crh.generatePDF')}}" method="POST">
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
            const ctx = document.getElementById('tmaChart').getContext('2d');
            const tmaChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [
                        {
                            label: 'Hujan Biasa',
                            data: @json($hujan_biasa),
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2,
                            fill: false
                        },
                        {
                            label: 'Hujan Otomatis',
                            data: @json($hujan_otomatis),
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
                                text: 'Curah Hujan (mm)'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endpush