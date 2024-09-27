@extends('layouts.admin')

@section('title')
    List Data Klimatologi ({{$pos->nama}})
@endsection

@push('css')
    
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Mulai Tanggal :</label>
                            <input type="date" name="start_date" id="start_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Sampai Tanggal :</label>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <button type="submit" class="btn btn-primary w-100" style="margin-top: 30px;">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item w-50 text-center">
                    <a class="nav-link active" href="#home" role="tab" data-toggle="tab"><b>Table</b></a>
                </li>
                <li class="nav-item w-50 text-center">
                    <a class="nav-link" href="#profile" role="tab" data-toggle="tab"><b>Grafik</b></a>
                </li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade show active" id="home">
                    <div class="d-flex justify-content-between mb-3 mt-3">
                        <div>
                            @role('Admin')
                                <a href="{{route('pos.klimatologi.createKlimatologi', ['id' => $pos->id])}}" class="btn btn-primary"><i class="fas fa-plus"></i> Input Data TMA</a>
                            @endrole
                            @if (!empty($start_date) && !empty($end_date))
                                <a href="{{route('pos.klimatologi.generatePDFKlima', ['start_date' => $start_date, 'end_date' => $end_date, 'id' => $pos->id])}}" class="btn btn-danger"><i class="fas fa-file-pdf"></i> Unduh PDF</a>
                                <a href="{{route('pos.klimatologi.generateExcelKlima', ['start_date' => $start_date, 'end_date' => $end_date, 'id' => $pos->id])}}" class="btn btn-success"><i class="fas fa-file-excel"></i> Unduh Excel</a>
                            @else
                                <a href="{{route('pos.klimatologi.generatePDFKlima', ['start_date' => \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'), 'end_date' => \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'), 'id' => $pos->id])}}" class="btn btn-danger"><i class="fas fa-file-pdf"></i> Unduh PDF</a>
                                <a href="{{route('pos.klimatologi.generateExcelKlima', ['start_date' => \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'), 'end_date' => \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'), 'id' => $pos->id])}}" class="btn btn-success"><i class="fas fa-file-excel"></i> Unduh Excel</a>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover text-center" id="table" style="width: 100%">
                            <thead class="bg-primary">
                                <tr>
                                    <th class="text-white" rowspan="3">No</th>
                                    <th class="text-white" rowspan="3">Tanggal</th>
                                    <th class="text-white" colspan="8">Thermometer</th>
                                    <th class="text-white" colspan="9">Psychrometer Standar</th>
                                    <th class="text-white" colspan="3">Thermometer Apung</th>
                                    <th class="text-white" colspan="3">Penguapan / Penakaran</th>
                                    <th class="text-white" colspan="1">Anemometer</th>
                                    <th class="text-white" colspan="2">Hujan</th>
                                    <th class="text-white" colspan="2">Sinar Matahari</th>
                                    <th class="text-white" rowspan="3">#</th>
                                </tr>
                                <tr>
                                    <th class="text-white" colspan="3" style="width: 3%">Maximum</th>
                                    <th class="text-white" rowspan="2" style="width: 3%">RT</th>

                                    <th class="text-white" colspan="3" style="width: 3%">Minimum</th>
                                    <th class="text-white" rowspan="2" style="width: 3%">RT</th>

                                    <th class="text-white" colspan="3" style="width: 3%">Bola Kering</th>
                                    <th class="text-white" rowspan="2" style="width: 3%">RT</th>
                                    <th class="text-white" colspan="3" style="width: 3%">Bola Basah</th>
                                    <th class="text-white" rowspan="2" style="width: 3%">RT</th>
                                    <th class="text-white" rowspan="2" style="width: 3%">RH%</th>
                                    
                                    <th class="text-white" rowspan="2" style="width: 3%">Max</th>
                                    <th class="text-white" rowspan="2" style="width: 3%">Min</th>
                                    <th class="text-white" rowspan="2" style="width: 3%">RT</th>

                                    <th class="text-white" rowspan="2" style="width: 3%">+</th>
                                    <th class="text-white" rowspan="2" style="width: 3%">-</th>
                                    <th class="text-white" rowspan="2" style="width: 3%">Hasil</th>

                                    <th class="text-white" rowspan="2" style="width: 3%">Spedometer</th>

                                    <th class="text-white" rowspan="2" style="width: 3%">Manual</th>
                                    <th class="text-white" rowspan="2" style="width: 3%">Otomatis</th>

                                    <th class="text-white" rowspan="2" style="width: 3%">Sinar Matahari	</th>
                                    <th class="text-white" rowspan="2" style="width: 3%">%</th>
                                </tr>
                                <tr>
                                    <th class="text-white" style="width: 3%">07</th>
                                    <th class="text-white" style="width: 3%">12</th>
                                    <th class="text-white" style="width: 3%">17</th>

                                    <th class="text-white" style="width: 3%">07</th>
                                    <th class="text-white" style="width: 3%">12</th>
                                    <th class="text-white" style="width: 3%">17</th>

                                    <th class="text-white" style="width: 3%">07</th>
                                    <th class="text-white" style="width: 3%">12</th>
                                    <th class="text-white" style="width: 3%">17</th>

                                    <th class="text-white" style="width: 3%">07</th>
                                    <th class="text-white" style="width: 3%">12</th>
                                    <th class="text-white" style="width: 3%">17</th>
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
                                        <td>
                                            @role('Admin')
                                                <div class="dropdown d-inline">
                                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Pilih
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item has-icon" href="{{route('pos.klimatologi.editKlima', ['id' => $item->id])}}"><i class="fas fa-pen"></i> Edit</a>
                                                        <a class="dropdown-item has-icon" href="javascript:void(0)" onclick="return deleteKlimatologi('{{$item->id}}')"><i class="fas fa-trash"></i> Hapus</a>
                                                    </div>
                                                </div>
                                            @endrole
                                            @role('Pimpinan')
                                                -
                                            @endrole
                                            @role('User')
                                                <div class="dropdown d-inline">
                                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Pilih
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item has-icon" href="javascript:void(0)" onclick="return deleteKlimatologi('{{$item->id}}')"><i class="fas fa-trash"></i> Hapus</a>
                                                    </div>
                                                </div>
                                            @endrole
                                        </td>
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
                <div role="tabpanel" class="tab-pane fade" id="profile">
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
@endsection

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

        function deleteKlimatologi(id) {
            swal({
                title: 'Peringatan !',
                text: 'Anda yakin ingin menghapus data ini?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    swal({
                        title: 'Konfimasi Password Anda.',
                            content: {
                                element: 'input',
                                attributes: {
                                placeholder: 'Masukan Password',
                                type: 'password',
                            },
                        },
                    }).then((data) => {
                        $.ajax({
                            url: `{{url('post/klimatologi/${id}/destroy')}}`,
                            method: "DELETE",
                            data: {
                                password: data
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.code == 200) {
                                    swal('Berhasil', response.message, 'success');
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 2000);
                                } else if(response.code == 400) {
                                    swal('Opps', response.message, 'error');
                                } else {
                                    swal('Opps', response.message, 'error');
                                }
                            },
                            error: function(err) {
                                console.log(err);
                            }
                        })
                    });
                }
            });
        }
    </script>
@endpush