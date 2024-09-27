@extends('layouts.admin')

@section('title')
    List Data Curah Hujan - ({{$pos->nama}})
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
                                <a href="{{route('pos.crh.createCRH', ['id' => $pos->id])}}" class="btn btn-primary"><i class="fas fa-plus"></i> Input Data Curah Hujan</a>
                            @endrole
                            @if (!empty($start_date) && !empty($end_date))
                                <a href="{{route('pos.crh.generatePDFCRH', ['start_date' => $start_date, 'end_date' => $end_date, 'id' => $pos->id])}}" class="btn btn-danger"><i class="fas fa-file-pdf"></i> Unduh PDF</a>
                                <a href="{{route('pos.crh.generateExcelTMA', ['start_date' => $start_date, 'end_date' => $end_date, 'id' => $pos->id])}}" class="btn btn-success"><i class="fas fa-file-excel"></i> Unduh Excel</a>
                            @else
                                <a href="{{route('pos.crh.generatePDFCRH', ['start_date' => \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'), 'end_date' => \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'), 'id' => $pos->id])}}" class="btn btn-danger"><i class="fas fa-file-pdf"></i> Unduh PDF</a>
                                <a href="{{route('pos.crh.generateExcelTMA', ['start_date' => \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'), 'end_date' => \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'), 'id' => $pos->id])}}" class="btn btn-success"><i class="fas fa-file-excel"></i> Unduh Excel</a>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover text-center">
                            <thead class="bg-primary">
                                <tr>
                                    <th class="text-white" rowspan="2">No</th>
                                    <th class="text-white" rowspan="2">Tanggal</th>
                                    <th class="text-white" colspan="2">Banyaknya Hujan</th>
                                    <th class="text-white" rowspan="2">Keterangan</th>
                                    <th class="text-white" rowspan="2">Dibuat</th>
                                    <th class="text-white" rowspan="2">Diupdate</th>
                                    <th class="text-white" rowspan="2">#</th>
                                </tr>
                                <tr>
                                    <th class="text-white">Otomatis (mm)</th>
                                    <th class="text-white">Biasa (mm)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($crhs as $item)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$item->tanggal}}</td>
                                        <td>{{$item->hujan_otomatis}}</td>
                                        <td>{{$item->hujan_biasa}}</td>
                                        <td>{{$item->keterangan}}</td>
                                        <td>{{$item->created_at}}</td>
                                        <td>{{$item->updated_at}}</td>
                                        <td>
                                            @role('Admin')
                                                <a href="{{route('pos.crh.editCRH', ['id' => $item->id])}}" class="btn btn-warning btn-sm mr-1"><i class="fas fa-pen"></i></a>
                                                <a href="javascript:void(0)" onclick="return deleteCRH('{{$item->id}}')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                                            @endrole
                                            @role('Pimpinan')
                                            -
                                            @endrole
                                            @role('User')
                                                <a href="javascript:void(0)" onclick="return deleteCRH('{{$item->id}}')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                                            @endrole
                                        </td>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('tmaChart').getContext('2d');
            const tmaChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [
                        {
                            label: 'Hujan Otomatis',
                            data: @json($hujan_otomatis),
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2,
                            fill: false
                        },
                        {
                            label: 'Hujan Biasa',
                            data: @json($hujan_biasa),
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
                                text: 'Tinggi Muka Air (cm)'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        });

        function deleteCRH(id) {
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
                            url: `{{url('post/crh/${id}/destroyCRH')}}`,
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