<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama DU/DI</th>
            <th>No Telp</th>
            <th>Email</th>
            <th>Alamat</th>
            <th>Jurusan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dudi as $d)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $d->nama_dudi }}</td>
                <td>{{ $d->no_telp }}</td>
                <td>{{ $d->email }}</td>
                <td>{{ $d->alamat }}</td>
                <td>{{ $d->jurusan->nama_jurusan }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
