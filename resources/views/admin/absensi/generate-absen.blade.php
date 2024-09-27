@extends('layouts.admin')

@section('title')
    Absensi Pos {{$pos->nama}}
@endsection

@push('css')
    <style>
        @media only screen and (max-width: 720px) {
            .signature-pad {
                width: 100%
            }
        }
    </style>
@endpush

@section('content')
    @if (session()->has('message'))
        <div class="alert alert-primary">
            {{session()->get('message')}}
        </div>
    @endif
    <div class="alert alert-primary">
        <i class="fas fa-info mr-2"></i>
        <b>
            Silahkan Klik Tombol Absensi Sebelum Melewati Tanggal {{\Carbon\Carbon::parse($tanggal)->format('d F Y')}}
        </b>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="" method="POST">
                @csrf
                <input type="hidden" name="pos_id" id="pos_id" value="{{$pos->id}}">
                <input type="hidden" name="jenis" id="jenis" value="{{$jenis}}">
                <input type="hidden" name="tanggal" id="tanggal" value="{{$tanggal}}">
                <div class="form-group mb-3">
                    <label for="" class="mb-2">Nama Pos</label>
                    <input type="text" class="form-control" value="{{$pos->nama}}" readonly>
                </div>
                <div class="form-group mb-3">
                    <label for="" class="mb-2">Nama User</label>
                    <input type="text" class="form-control" value="{{Auth::user()->name}}" readonly>
                </div>
                <div class="form-group mb-3">
                    <label for="" class="mb-2">Tanggal Absen</label>
                    <input type="text" class="form-control" value="{{$tanggal}}" readonly>
                </div>
                <label for="">Tanda Tangan</label>
                <div class="form-group mb-3">
                    <canvas id="signature-pad" style="border: 1px solid #8a8a8add" class="signature-pad" width="400" height="200"></canvas>
                </div>
                <button class="btn btn-danger" type="button" id="clear">Hapus</button>
                <button class="btn btn-primary" type="button" id="save">Absensi</button>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
    <script>
        $(function(){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            var canvas = document.getElementById('signature-pad');

            var signaturePad = new SignaturePad(canvas, {
            });

            var saveButton = document.getElementById('save');
            var clearButton = document.getElementById('clear');
            var pos_id = $("#pos_id").val();
            var jenis = $("#jenis").val();

            saveButton.addEventListener('click', function (event) {
                $.ajax({
                    url: "{{ route('admin.absensi.save') }}",
                    method: 'post',
                    data: {
                        signature: signaturePad.toDataURL('image/png'),
                        pos_id: $("#pos_id").val(),
                        tanggal: $("#tanggal").val(),
                        jenis: $("#jenis").val(),
                    },
                    success: function(result){
                        swal('Berhasil', result.success, 'success');
                        setTimeout(() => {
                            if (jenis === 'tmas') {
                                window.location.href = `{{url('post/tmas/${pos_id}/list')}}`;
                            } else if(jenis === 'crhs') {
                                window.location.href = `{{url('post/crh/${pos_id}/list')}}`;
                            } else {
                                window.location.href = `{{url('post/klimatologi/${pos_id}/show')}}`;
                            }
                        }, 2000);
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            });

            clearButton.addEventListener('click', function () {
                signaturePad.clear();
            });

        });
    </script>
@endpush