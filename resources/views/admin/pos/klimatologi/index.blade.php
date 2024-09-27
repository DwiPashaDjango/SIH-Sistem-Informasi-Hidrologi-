@extends('layouts.admin')

@section('title')
    Data Pos Klimatologi
@endsection

@push('css')
    <link rel="stylesheet" href="{{asset('admin')}}/modules/select2/dist/css/select2.min.css">
@endpush

@section('content')
    @if (session()->has('message'))
        <div class="alert alert-primary">
            {{session()->get('message')}}
        </div>
    @endif
    <div class="card card-primary">
        @role('Admin')
            <div class="card-header">
                <a href="{{route('pos.create', ['jenisPos' => 'klimatologis'])}}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</a>
            </div>
        @endrole
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3">
                    <select name="provinces_id" id="provinces_id" class="form-control select2">
                        <option value="">- Pilih -</option>
                        @foreach ($province as $prv)
                            <option value="{{$prv->id}}">{{$prv->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3">
                    <select name="regencies_id" id="regencies_id" class="form-control select2">
                        <option value="">- Pilih -</option>
                    </select>
                </div>
                <div class="col-lg-3">
                    <select name="subdas_id" id="subdas_id" class="form-control select2">
                        <option value="">- Pilih -</option>
                        @foreach ($subdas as $sbd)
                            <option value="{{$sbd->id}}">{{$sbd->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3">
                    <button type="button" id="filter" class="btn btn-primary w-100 mt-1">Filter</button>
                </div>
            </div>
            <hr class="divide">
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
                            <th class="text-center text-white">Subdas</th>
                            <th class="text-center text-white">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{asset('admin')}}/modules/select2/dist/js/select2.full.min.js"></script>
    <script>
        $(document).ready(function() {
            let table = $("#table").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{route('pos.klimatologi')}}",
                    type: 'GET',
                    data: function(d) {
                        d.provinces_id = $('#provinces_id').val();
                        d.regencies_id = $('#regencies_id').val();
                        d.subdas_id = $('#subdas_id').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'image', name: 'image' },
                    { data: 'name', name: 'name' },
                    { data: 'cordinat', name: 'cordinat' },
                    { data: 'location', name: 'location' },
                    { data: 'regency', name: 'regency' },
                    { data: 'province', name: 'province' },
                    { data: 'subdas', name: 'subdas' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    { targets: [0, 1], orderable: false }
                ],
                order: [[1, 'desc']],
            })

            $("#filter").click(function() {
                table.draw()
            })

            $("#provinces_id").change(function() {
                let provinces_id = $(this).val();

                $.ajax({
                    url: "{{route('pos.tma.getRegencie')}}",
                    method: 'POST',
                    data: {provinces_id: provinces_id},
                    dataType: 'json',
                    success: function(response) {
                        let html = '';
                        html += '<option value="">- Pilih -</option>';
                        $.each(response.data, function(index, value) {
                            html += `<option value="${value.id}">${value.name}</option>`;
                        })
                        $("#regencies_id").html(html)
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
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
                                url: `{{url('post/tmas/${id}/destroy')}}`,
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