@extends('layouts.admin')

@section('title')
    Edit Data Klimatologi ({{$klimatologis->pos->nama}})
@endsection

@push('css')
    
@endpush

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>
                        {{$error}}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session()->get('message'))
        <div class="alert alert-primary">
            {{session()->get('message')}}
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <a href="{{route('pos.klimatologi.show', ['id' => $klimatologis->pos->id])}}" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-body">
            <form class="mt-4" action="{{route('pos.klimatologi.updateKilma', ['id' => $klimatologis->id])}}" method="POST">
                @csrf
                @method("PUT")
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{$klimatologis->tanggal}}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="jam" class="form-label">Jam</label>
                            <select id="jam" name="jam" class="form-control" required class="form-select">
                                <option value="07.00">07.00</option>
                                <option value="12.00">12.00</option>
                                <option value="17.00">17.00</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Thermometer Section -->
                <div class="row">
                    <div class="col">
                        <p><strong>1. Thermometer</strong></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="termo_max" class="form-label">Termometer Maksimal</label>
                            <input id="termo_max" name="termo_max" placeholder="Termometer Maksimal ..." type="text" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="termo_min" class="form-label">Termometer Minimal</label>
                            <input id="termo_min" name="termo_min" placeholder="Termometer Minimal ..." type="text" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Psychrometer Section -->
                <div class="row">
                    <div class="col">
                        <p><strong>2. Psychrometer Standar</strong></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="bola_kering" class="form-label">Bola Kering</label>
                            <input id="bola_kering" name="bola_kering" placeholder="Bola Kering ..." type="text" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="bola_basah" class="form-label">Bola Basah</label>
                            <input id="bola_basah" name="bola_basah" placeholder="Bola Basah ..." type="text" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="rh" class="form-label">Rh (%)</label>
                            <input id="rh" name="rh" placeholder="Rh (%) ..." type="text" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Thermometer Apung Section -->
                <div class="row">
                    <div class="col">
                        <p><strong>3. Thermometer Apung</strong></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="termo_apung_max" class="form-label">Termometer Apung Maksimal</label>
                            <input id="termo_apung_max" name="termo_apung_max" placeholder="Termometer Apung Maksimal ..." type="text" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="termo_apung_min" class="form-label">Termometer Apung Minimal</label>
                            <input id="termo_apung_min" name="termo_apung_min" placeholder="Termometer Apung Minimal ..." type="text" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Penguapan Section -->
                <div class="row">
                    <div class="col">
                        <p><strong>4. Penguapan</strong></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="penguapan_plus" class="form-label">Penguapan (+)</label>
                            <input id="penguapan_plus" name="penguapan_plus" placeholder="Penguapan (+) ..." type="text" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="penguapan_min" class="form-label">Penguapan (-)</label>
                            <input id="penguapan_min" name="penguapan_min" placeholder="Penguapan (-) ..." type="text" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Anemometer Section -->
                <div class="row">
                    <div class="col">
                        <p><strong>5. Anemometer</strong></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="anemometer_spedometer" class="form-label">Spedometer</label>
                            <input id="anemometer_spedometer" name="anemometer_spedometer" placeholder="Spedometer ..." type="text" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Hujan Section -->
                <div class="row">
                    <div class="col">
                        <p><strong>6. Hujan</strong></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="hujan_otomatis" class="form-label">Hujan Otomatis</label>
                            <input id="hujan_otomatis" name="hujan_otomatis" placeholder="Hujan Otomatis ..." type="text" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="hujan_biasa" class="form-label">Hujan Manual</label>
                            <input id="hujan_biasa" name="hujan_biasa" placeholder="Hujan Manual ..." type="text" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Penyinaran Section -->
                <div class="row">
                    <div class="col">
                        <p><strong>7. Penyinaran</strong></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="sinar_matahari" class="form-label">Sinar Matahari</label>
                            <input id="sinar_matahari" name="sinar_matahari" placeholder="Penyinaran Matahari ..." type="text" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Lainnya Section -->
                <div class="row">
                    <div class="col">
                        <p><strong>8. Lainnya</strong></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea id="keterangan" placeholder="Masukkan Keterangan..." name="keterangan" class="form-control"></textarea>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Ubah</button>
            </form>

        </div>
    </div>
@endsection

@push('js')
    
@endpush