@extends('layouts.admin')

@section('title')
    Data Users
@endsection

@push('css')
    <link rel="stylesheet" href="{{asset('admin')}}/modules/select2/dist/css/select2.min.css">
@endpush

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <a href="{{route('users')}}" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{route('users.store')}}">
                @csrf
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input id="name" name="name" placeholder="Masukkan Nama..." type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input id="username" name="username" placeholder="Masukkan Username..." type="text" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" name="email" placeholder="Masukkan Email..." type="email" class="form-control">
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="nohp" class="form-label">No. HP <em>(Format: 08xxx)</em></label>
                            <input id="nohp" name="nohp" placeholder="Masukkan No HP..." type="text" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="roles" class="form-label">Level</label>
                            <select id="roles" name="roles" class="form-control select2">
                                <option value="">- Pilih -</option>
                                @foreach ($roles as $rls)
                                    <option value="{{$rls->name}}">{{$rls->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="jenis" class="form-label">Jenis</label>
                            <select id="jenis_id" name="jenis_id" class="form-control select2">
                                <option value="">--Pilih--</option>
                                @foreach ($jenis as $jns)
                                    <option value="{{$jns->id}}">{{$jns->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="pos" class="form-label">Pos</label>
                            <select id="pos_id" name="pos_id" class="form-control select2">
                                <option value="">--Pilih--</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" name="password" placeholder="Masukkan Password..." type="password" class="form-control">
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input id="password_confirmation" name="password_confirmation" placeholder="Masukkan Konfirmasi Password..." type="password" class="form-control">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Tambah</button>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{asset('admin')}}/modules/select2/dist/js/select2.full.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#jenis_id").change(function() {
                let jenis_id = $(this).val();
                $.ajax({
                    url: "{{route('users.getPosByJenis')}}",
                    method: "POST",
                    data: {jenis_id: jenis_id},
                    dataType: "json",
                    success: function(data) {
                        let html = '';
                        $.each(data.data, function(index, value) {
                            html += `<option value="${value.id}">${value.nama}</option>`;
                        })
                        $("#pos_id").html(html)
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            })
        })
    </script>
@endpush