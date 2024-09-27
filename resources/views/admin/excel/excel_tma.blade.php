<table>
    <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Pagi</th>
        <th>Siang</th>
        <th>Sore</th>
        <th>Harian(RT)</th>
        <th>Keterangan</th>
        <th>Dibuat</th>
        <th>Diupdate</th>
    </tr>
    @foreach ($tmas as $item)
        @php
            $averagePerRow = ($item->pagi + $item->siang + $item->sore) / 3;
        @endphp
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$item->tanggal}}</td>
            <td>{{$item->pagi}} cm</td>
            <td>{{$item->siang}} cm</td>
            <td>{{$item->sore}} cm</td>
            <td>{{number_format($averagePerRow, 2)}} cm</td>
            <td>{{$item->keterangan}}</td>
            <td>{{$item->created_at}}</td>
            <td>{{$item->updated_at}}</td>
        </tr>
    @endforeach
</table>