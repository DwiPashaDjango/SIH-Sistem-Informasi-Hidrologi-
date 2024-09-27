<table>
    <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Hujan Otomatis</th>
        <th>Hujan Biasa</th>
        <th>Keterangan</th>
        <th>Dibuat</th>
        <th>Diupdate</th>
    </tr>
    @foreach ($crhs as $item)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$item->tanggal}}</td>
            <td>{{$item->hujan_otomatis}}</td>
            <td>{{$item->hujan_biasa}}</td>
            <td>{{$item->keterangan}}</td>
            <td>{{$item->created_at}}</td>
            <td>{{$item->updated_at}}</td>
        </tr>
    @endforeach
</table>