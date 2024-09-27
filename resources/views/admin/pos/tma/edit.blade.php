@extends('layouts.admin')

@section('title')
    Pos {{$pos->nama}}
@endsection

@push('css')
    <link rel="stylesheet" href="{{asset('admin')}}/modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
    <link rel="stylesheet" href="{{asset('admin')}}/modules/select2/dist/css/select2.min.css">
@endpush

@section('content')
    @if (session()->has('message'))
        <div class="alert alert-primary">
            {{session()->get('message')}}
        </div>
    @endif
    <div class="card card-primary">
        <div class="card-body">
            <form action="{{route('pos.tma.update', ['id' => $pos->id])}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method("PUT")
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Nama</label>
                            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{$pos->nama}}" placeholder="Masukan Nama...">
                            @error('nama')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Jenis</label>
                            <select name="jenis_id" id="jenis_id" class="form-control @error('jenis_id') is-invalid @enderror">
                                <option value="">- Pilih -</option>
                                @foreach ($jenis as $item)
                                    <option value="{{$item->id}}" {{$pos->jenis_id == $item->id ? 'selected' : ''}}>{{$item->nama}}</option>
                                @endforeach
                            </select>
                            @error('jenis_id')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Koordinat X</label>
                            <input type="text" name="koordinatx" id="koordinatx" value="{{$pos->koordinatx}}" class="form-control @error('koordinatx') is-invalid @enderror" placeholder="Masukan Koordinat X...">
                            @error('koordinatx')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Koordinat Y</label>
                            <input type="text" name="koordinaty" id="koordinaty" value="{{$pos->koordinaty}}" class="form-control @error('koordinaty') is-invalid @enderror" placeholder="Masukan Koordinat Y...">
                            @error('koordinaty')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Lokasi</label>
                            <textarea name="lokasi" id="lokasi" class="form-control @error('lokasi') is-invalid @enderror" cols="30" rows="10" placeholder="Masukan Lokasi...">{{$pos->lokasi}}</textarea>
                            @error('lokasi')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Provinsi</label>
                            <select name="provinces_id" id="provinces_id" class="form-control @error('provinces_id') is-invalid @enderror select2">
                                <option value="">- Pilih -</option>
                                @foreach ($province as $prv)
                                    <option value="{{$prv->id}}" {{$pos->provinces_id == $prv->id ? 'selected' : ''}}>{{$prv->name}}</option>
                                @endforeach
                            </select>
                            @error('provinces_id')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Kabupaten / Kota</label>
                            <select name="regencies_id" id="regencies_id" class="form-control @error('regencies_id') is-invalid @enderror select2">
                                <option value="">- Pilih -</option>
                            </select>
                            @error('regencies_id')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Subdas</label>
                            <select name="subdas_id" id="subdas_id" class="form-control @error('subdas_id') is-invalid @enderror select2">
                                <option value="">- Pilih -</option>
                                @foreach ($subdas as $sbd)
                                    <option value="{{$sbd->id}}" {{$pos->subdas_id == $sbd->id ? 'selected' : ''}}>{{$sbd->name}}</option>
                                @endforeach
                            </select>
                            @error('subdas_id')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group mb-3">
                            @if ($jenisPos === 'crhs')
                                {{-- <label for="" class="mb-2">Hujan Ringan (mm)</label> --}}
                            @else
                                <label for="" class="mb-2">Normal (mm)</label>
                                <input type="text" name="normal" id="normal" value="{{$pos->normal}}" class="form-control @error('normal') is-invalid @enderror" placeholder="Masukan Normal...">
                            @endif
                            @error('normal')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group mb-3">
                            @if ($jenisPos === 'crhs')
                                {{-- <label for="" class="mb-2">Hujan Sedang (mm)</label> --}}
                            @else
                                <label for="" class="mb-2">Waspada (mm)</label>
                                <input type="text" name="waspada" id="waspada" value="{{$pos->waspada}}" class="form-control @error('waspada') is-invalid @enderror" placeholder="Masukan Waspada...">
                            @endif
                            @error('waspada')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group mb-3">
                            @if ($jenisPos === 'crhs')
                                {{-- <label for="" class="mb-2">Hujan Lebat (mm)</label> --}}
                            @else
                                <label for="" class="mb-2">Siaga (mm)</label>
                                <input type="text" name="siaga" id="siaga" value="{{$pos->siaga}}" class="form-control @error('siaga') is-invalid @enderror" placeholder="Masukan Siaga...">
                            @endif
                            @error('siaga')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group mb-3">
                            @if ($jenisPos === 'crhs')
                                {{-- <label for="" class="mb-2">Hujan Ekstrim (mm)</label> --}}
                            @else
                                <label for="" class="mb-2">Awas (mm)</label>
                                <input type="text" name="awas" id="awas" value="{{$pos->awas}}" class="form-control @error('awas') is-invalid @enderror" placeholder="Masukan Awas ...">
                            @endif
                            @error('awas')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group mb-3">
                            @if ($jenisPos === 'tmas')
                                <label for="formFile" class="form-label mb-2">TMA Banjir (mm)</label>
                            @elseif($jenisPos === 'crhs')
                                <label for="formFile" class="form-label mb-2">Status Curah Hujan</label>
                            @elseif($jenisPos === 'klimatologis')
                                <label for="formFile" class="form-label mb-2">Status Klimatologi</label>
                            @endif
                            <input type="text" name="tma_banjir" id="tma_banjir" value="{{$pos->tma_banjir}}" class="form-control @error('tma_banjir') is-invalid @enderror" placeholder="Masukan TMA Banjir ...">
                            @error('tma_banjir')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group mb-3">
                            <label for="formFile" class="form-label mb-2">Gambar</label>
                            <input type="file" name="gambar" id="gambar" class="form-control @error('gambar') is-invalid @enderror" placeholder="Masukan gambar ...">
                            @error('gambar')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
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
        let provinces_id = "{{$pos->provinces_id}}";
        let regencies_id = "{{$pos->regencies_id}}";

        getProvince(provinces_id)

        $("#provinces_id").change(function() {
            provinces_id = $(this).val();
            getProvince(provinces_id);
        })
        
        function getProvince(provinces_id) {
            $.ajax({
                url: "{{route('pos.tma.getRegencie')}}",
                method: 'POST',
                data: {provinces_id: provinces_id},
                dataType: 'json',
                success: function(response) {
                    let html = '';
                    html += '<option value="">- Pilih -</option>';
                    $.each(response.data, function(index, value) {
                        html += `<option value="${value.id}" ${regencies_id == value.id ? 'selected' : ''}>${value.name}</option>`;
                    })
                    $("#regencies_id").html(html)
                },
                error: function(err) {
                    console.log(err);
                }
            })
        }
    </script>
@endpush