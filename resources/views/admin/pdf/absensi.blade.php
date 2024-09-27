<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Absensi Petugas {{$jenis->nama}} Bulan {{$month}} Tahun {{$years}}</title>
    <link rel="stylesheet" href="admin//modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin/css/style.css">
    <link rel="stylesheet" href="admin/css/components.css">
    <link rel="stylesheet" href="admin/modules/fontawesome/css/all.min.css">
    <style>
        body {
            font-family: Arial;
        }
    </style>
</head>
<body>
    <div class="contianer">
        <img src="{{public_path('kop.png')}}" width="500" alt="">
        <hr style="border: 1px solid #444444dd">

        @php
            $dateString = $years . '-' . $month;
        @endphp
        <h4 class="text-primary mt-5 text-center mb-2">Rekap Absensi Petugas Pos {{$jenis->nama}} Bulan {{\Carbon\Carbon::parse($dateString)->translatedFormat('F')}} Tahun {{\Carbon\Carbon::parse($dateString)->translatedFormat('Y')}}</h4>
        
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

        <table class="table table-bordered table-striped table-sm text-center mt-3">
            <thead class="bg-primary">
                <tr>
                    <th class="text-white text-start">Nama Pos</th>
                    @for($date = $startDate->copy(); $date->lte($endDate); $date->addDay())
                        <th style="width: 2% !important; min-width: 5px !important; font-size: 14px" class="text-white">
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
                        <td>{{ $data['nama'] }}</td>
                        @for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay())
                            @php
                                $formattedDate = $date->format('Y-m-d');
                            @endphp
                            <td>
                                @if (isset($data['presence'][$formattedDate]))
                                    <a href="{{ asset('tanda_tangan/' . $data['presence'][$formattedDate]['image']) }}" target="_blank">
                                        <i class="fas fa-check text-success"></i>
                                    </a>
                                @else
                                    <a href="javascript:void(0)">
                                        <i class="fas fa-window-close text-danger"></i>
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
</body>
</html>