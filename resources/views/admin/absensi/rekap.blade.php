@extends('layouts.admin')

@section('title')
    Rekap Absensi
@endsection

@push('css')
    <link rel="stylesheet" href="{{asset('admin')}}/modules/select2/dist/css/select2.min.css">
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Rekap Absensi Bulanan</h4>
        </div>
        <div class="card-body">
            <form action="">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Jenis Pos</label>
                            <select name="jenis_id" id="jenis_id" class="form-control select2">
                                <option value="">- Pilih -</option>
                                @foreach ($jenis as $item)
                                    <option value="{{$item->id}}">{{$item->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Bulan</label>
                            <select name="month" id="month" class="form-control select2">
                                <option value="">- Pilih -</option>
                                @php
                                    $months = [
                                        '01' => 'Januari',
                                        '02' => 'Februari',
                                        '03' => 'Maret',
                                        '04' => 'April',
                                        '05' => 'Mei',
                                        '06' => 'Juni',
                                        '07' => 'Juli',
                                        '08' => 'Agustus',
                                        '09' => 'September',
                                        '10' => 'Oktober',
                                        '11' => 'November',
                                        '12' => 'Desember'
                                    ];
        
                                    foreach ($months as $key => $month) {
                                        echo "<option value='{$key}'>{$month}</option>";
                                    }
                                @endphp     
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Tahun</label>
                            <select name="years" id="years" class="form-control select2">
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
                    <div class="col-lg-3" style="padding-top: 30px">
                        <button type="submit" id="filter" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @php
        $startDate = \Carbon\Carbon::parse($startDate)->startOfMonth();
        $endDate = \Carbon\Carbon::parse($endDate)->endOfMonth();

        $grouped_data = [];

        foreach ($posts as $post) {
            if (!isset($grouped_data[$post->id])) {
                $grouped_data[$post->id] = [
                    'nama' => $post->nama,
                    'presence' => []
                ];
            }

            foreach ($post->absen as $absen) {
                $created_at = new \DateTime($absen->tanggal);
                $grouped_data[$post->id]['presence'][$created_at->format('Y-m-d')] = [
                    'image' => $absen->file
                ];
            }
        }
    @endphp

    <div class="card">
        @if (count($grouped_data) > 0)
            <div class="card-header">
                <a href="{{route('admin.rekap.absen.generatePdf', ['jenis_id' => $jenis_id, 'month' => $bulan, 'years' => $years])}}" class="btn btn-danger"><i class="fas fa-file-pdf mr-2"></i> Unduh PDF</a>
            </div>
        @endif
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm text-center">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-white">No</th>
                            <th class="text-white text-start">Nama Pos</th>
                            @for($date = $startDate->copy(); $date->lte($endDate); $date->addDay())
                                <th style="width: 2% !important; min-width: 5px !important; font-size: 12px" class="text-white">
                                    <span>{{$date->format('j')}}</span>
                                </th>
                            @endfor
                            <th class="text-white">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($grouped_data as $data)
                            @php
                                $jumlahHadir = 0;
                                foreach ($data['presence'] as $tanggal => $absen) {
                                    $jumlahHadir++;
                                }
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data['nama'] }}</td>
                                @for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay())
                                    @php
                                        $formattedDate = $date->format('Y-m-d');
                                    @endphp
                                    <td>
                                        @if (isset($data['presence'][$formattedDate]))
                                            <a href="{{ asset('tanda_tangan/' . $data['presence'][$formattedDate]['image']) }}" target="_blank" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        @else
                                            <a href="javascript:void(0)" class="btn btn-danger btn-sm">
                                                <i class="fas fa-window-close"></i>
                                            </a>
                                        @endif
                                    </td>
                                @endfor
                                <td>
                                    {{$jumlahHadir}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100">Silahkan Lakukan Filter Jenis Pos, Bulan Dan Tahun Untuk Melakukan Rekap Absensi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
   <script src="{{asset('admin')}}/modules/select2/dist/js/select2.full.min.js"></script>
@endpush