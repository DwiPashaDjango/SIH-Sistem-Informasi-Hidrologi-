<table>
    <thead>
        <tr>
            <th class="text-white" rowspan="3">No</th>
            <th class="text-white" rowspan="3">Tanggal</th>
            <th class="text-white" colspan="8">Thermometer</th>
            <th class="text-white" colspan="9">Psychrometer Standar</th>
            <th class="text-white" colspan="3">Thermometer Apung</th>
            <th class="text-white" colspan="3">Penguapan / Penakaran</th>
            <th class="text-white" colspan="1">Anemometer</th>
            <th class="text-white" colspan="2">Hujan</th>
            <th class="text-white" colspan="2">Sinar Matahari</th>
        </tr>
        <tr>
            <th class="text-white" colspan="3">Maximum</th>
            <th class="text-white" rowspan="2">RT</th>

            <th class="text-white" colspan="3">Minimum</th>
            <th class="text-white" rowspan="2">RT</th>

            <th class="text-white" colspan="3">Bola Kering</th>
            <th class="text-white" rowspan="2">RT</th>
            <th class="text-white" colspan="3">Bola Basah</th>
            <th class="text-white" rowspan="2">RT</th>
            <th class="text-white" rowspan="2">RH%</th>
            
            <th class="text-white" rowspan="2">Max</th>
            <th class="text-white" rowspan="2">Min</th>
            <th class="text-white" rowspan="2">RT</th>

            <th class="text-white" rowspan="2">+</th>
            <th class="text-white" rowspan="2">-</th>
            <th class="text-white" rowspan="2">Hasil</th>

            <th class="text-white" rowspan="2">Spedometer</th>

            <th class="text-white" rowspan="2">Manual</th>
            <th class="text-white" rowspan="2">Otomatis</th>

            <th class="text-white" rowspan="2">Sinar Matahari	</th>
            <th class="text-white" rowspan="2">%</th>
        </tr>
        <tr>
            <th class="text-white">07</th>
            <th class="text-white">12</th>
            <th class="text-white">17</th>

            <th class="text-white">07</th>
            <th class="text-white">12</th>
            <th class="text-white">17</th>

            <th class="text-white">07</th>
            <th class="text-white">12</th>
            <th class="text-white">17</th>

            <th class="text-white">07</th>
            <th class="text-white">12</th>
            <th class="text-white">17</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($klimatologis as $item)
            @php
                $averageTermoMax = ($item->termo_max_pagi + $item->termo_max_siang + $item->termo_max_sore) / 3;
                $averageTermoMin = ($item->termo_min_pagi + $item->termo_min_siang + $item->termo_min_sore) / 3;
                $averageBolaKering = ($item->bola_kering_pagi + $item->bola_kering_siang + $item->bola_kering_sore) / 3;
                $averageBolaBasah = ($item->bola_basah_pagi + $item->bola_basah_siang + $item->bola_basah_sore) / 3;
                $averageTermoApung = ($item->termo_apung_max + $item->termo_apung_min) / 2;
                $hasilPenguapan = ($item->penguapan_plus + $item->penguapan_min);
            @endphp
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$item->tanggal}}</td>
                <td>{{$item->termo_max_pagi}}</td>
                <td>{{$item->termo_max_siang}}</td>
                <td>{{$item->termo_max_sore}}</td>
                <td>{{number_format($averageTermoMax, 2)}}</td>
                <td>{{$item->termo_min_pagi}}</td>
                <td>{{$item->termo_min_siang}}</td>
                <td>{{$item->termo_min_sore}}</td>
                <td>{{number_format($averageTermoMin, 2)}}</td>
                <td>{{$item->bola_kering_pagi}}</td>
                <td>{{$item->bola_kering_siang}}</td>
                <td>{{$item->bola_kering_sore}}</td>
                <td>{{number_format($averageBolaKering, 2)}}</td>
                <td>{{$item->bola_basah_pagi}}</td>
                <td>{{$item->bola_basah_siang}}</td>
                <td>{{$item->bola_basah_sore}}</td>
                <td>{{number_format($averageBolaBasah, 2)}}</td>
                <td>{{$item->rh}}</td>
                <td>{{$item->termo_apung_max}}</td>
                <td>{{$item->termo_apung_min}}</td>
                <td>{{number_format($averageTermoApung, 2)}}</td>
                <td>{{$item->penguapan_plus}}</td>
                <td>{{$item->penguapan_min}}</td>
                <td>{{number_format($hasilPenguapan)}}</td>
                <td>{{ number_format($item->anemometer_spedometer) }}</td>
                <td>{{$item->hujan_otomatis}}</td>
                <td>{{$item->hujan_biasa}}</td>
                <td>{{$item->sinar_matahari}}</td>
                <td>{{number_format($item->sinar_matahari, 1)}}</td>
            </tr>
        @endforeach
    </tbody>
</table>