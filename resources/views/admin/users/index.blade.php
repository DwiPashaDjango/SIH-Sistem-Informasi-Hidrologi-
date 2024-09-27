@extends('layouts.admin')

@section('title')
    Data Users
@endsection

@push('css')
    
@endpush

@section('content')
    @if (session()->has('message'))
        <div class="alert alert-primary">
            {{session()->get('message')}}
        </div>
    @endif
    <div class="card">
        @role('Admin')
            <div class="card-header">
                <a href="{{route('users.create')}}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</a>
            </div>
        @endrole
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center" id="table" style="width: 100%">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-center text-white">No</th>
                            <th class="text-center text-white">Nama</th>
                            <th class="text-center text-white">Username</th>
                            <th class="text-center text-white">Email</th>
                            <th class="text-center text-white">No Handphone</th>
                            <th class="text-center text-white">Pos</th>
                            <th class="text-center text-white">Level</th>
                            <th class="text-center text-white">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            let table = $("#table").DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{route('users')}}",
                    method: "GET"
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'username', name: 'username' },
                    { data: 'email', name: 'email' },
                    { data: 'telp', name: 'telp' },
                    { data: 'pos', name: 'pos' },
                    { data: 'role', name: 'role' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    { targets: [0, 1], orderable: false }
                ],
                order: [[1, 'desc']],
            })

            $(document).on('click', '#delete', function() {
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
                                url: `{{url('users/${id}/destroy')}}`,
                                method: 'DELETE',
                                data: {
                                    password: data
                                },
                                dataType: "json",
                                success: function(response) {
                                    console.log(response);
                                    if (response.code == 200) {
                                        swal('Berhasil', response.message, 'success');
                                        table.draw();
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