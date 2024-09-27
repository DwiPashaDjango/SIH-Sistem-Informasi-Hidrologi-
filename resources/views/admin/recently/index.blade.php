@extends('layouts.admin')

@section('title')
    Recently Pos
@endsection

@push('css')
    
@endpush

@section('content')
    <div class="card card-primary">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center" id="table" style="width: 100%">
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
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            let table = $("#table").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{route('recently.pos')}}",
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

            $(document).on('click', '#restore', function() {
                let id = $(this).data('id');
                swal({
                    title: 'Peringatan !',
                    text: 'Anda yakin ingin mengembalikan data ini?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: `{{url('recently-pos/${id}/restore')}}`,
                            method: 'PUT',
                            dataType: 'json',
                            success: function(response) {
                                swal('Berhasil', response.message, 'success');
                                table.draw()
                            },
                            error: function(err) {
                                console.log(err);
                            }
                        })
                    }
                });
            })
        })
    </script>
@endpush