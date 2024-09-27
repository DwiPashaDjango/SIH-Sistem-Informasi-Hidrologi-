@extends('layouts.admin')

@section('title')
    Kualitas Air
@endsection

@push('css')
    
@endpush

@section('content')
    <div class="card">
        @role('Admin')
            <div class="card-header">
                <a href="{{route('pos.create', ['jenisPos' => 'tmas'])}}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</a>
            </div>
        @endrole
        <div class="card-body">
            <table class="table table-bordered table-striped text-center" id="table">
                <thead class="bg-primary">
                        <tr>
                            <th class="text-center text-white">No</th>
                            <th class="text-center text-white">Gambar</th>
                            <th class="text-center text-white">Nama</th>
                            <th class="text-center text-white">Titik Kordinat</th>
                            <th class="text-center text-white">Lokasi</th>
                            <th class="text-center text-white">Kab/Kota</th>
                            <th class="text-center text-white">Provinsi</th>
                            <th class="text-center text-white">#</th>
                        </tr>
                    </thead>
            </table>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            let table = $("#table").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{route('water.quality')}}",
                    type: 'GET'
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'image', name: 'image' },
                    { data: 'name', name: 'name' },
                    { data: 'cordinat', name: 'cordinat' },
                    { data: 'location', name: 'location' },
                    { data: 'regency', name: 'regency' },
                    { data: 'province', name: 'province' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    { targets: [0, 1], orderable: false }
                ],
                order: [[1, 'desc']],
            })

            $(document).on('click', '#deletePosInQualityWater', function(e) {
                let id = $(this).data('id');

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
                                url: `{{url('water-quality/${id}/deletePosInQualityWater')}}`,
                                method: 'DELETE',
                                data: {
                                    password: data
                                },
                                dataType: "json",
                                success: function(response) {
                                    console.log(response);
                                    if (response.code == 200) {
                                        swal('Berhasil', response.message, 'success');
                                        table.draw()
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
            })
        })
    </script>
@endpush