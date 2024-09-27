@extends('layouts.admin')

@section('title')
    Kualitas Air - Pos {{$pos->nama}}
@endsection

@push('css')
    
@endpush

@section('content')
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
                <li class="nav-item w-50 text-center">
                    <a class="nav-link active" href="#home" role="tab" data-toggle="tab"><b>Table</b></a>
                </li>
                <li class="nav-item w-50 text-center">
                    <a class="nav-link" href="#profile" role="tab" data-toggle="tab"><b>Grafik</b></a>
                </li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade show active" id="home">
                    <a href="{{route('water.quality.testQualityWater', ['id' => $pos->id])}}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Buat Uji Kualitas Air</a>
                    <hr class="divide">
                    <div class="table-responsive">
                         <table class="table table-bordered table-striped table-hover text-center">
                            <thead class="bg-primary">
                                <tr>
                                    <th class="text-white">No</th>
                                    <th class="text-white">Semester</th>
                                    <th class="text-white">Tanggal</th>
                                    <th class="text-white">Hasil Uji</th>
                                    <th class="text-white">Status</th>
                                    <th class="text-white">#</th>
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
                                            <a href="{{route('water.quality.generatePDFQualityWater', ['id' => $item->id])}}" class="btn btn-info btn-sm"><i class="fas fa-file-pdf"></i></a>
                                            <a href="{{route('water.quality.edit', ['id' => $item->id])}}" class="btn btn-warning btn-sm"><i class="fas fa-pen"></i></a>
                                            <a href="javascript:void(0)" onclick="return deleteItem('{{$item->id}}')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
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
                <div role="tabpanel" class="tab-pane fade" id="profile">
                    <div style="width: 100%; margin: auto;">
                        <canvas id="tmaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

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

        function deleteItem(id) {
            swal({
                title: 'Peringtan !',
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
                            url: `{{url('water-quality/${id}/destroy')}}`,
                            method: 'DELETE',
                            data: {
                                password: data
                            },
                            dataType: "json",
                            success: function(response) {
                                console.log(response);
                                if (response.code == 200) {
                                    swal('Berhasil', response.message, 'success');
                                    window.location.reload()
                                } else if(response.code == 400) { 
                                    swal('Oppps', response.message, 'error');
                                } else if(response.code == 404) {
                                    swal('Oppps', response.message, 'error');
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