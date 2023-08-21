<table>
    <thead>
        <tr>
            <th>No</th>
            <th>NIP</th>
            <th>Nama guru</th>
            <th>Gender</th>
            <th>Jurusan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($guru as $g)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $g->nip }}</td>
                <td>{{ $g->nama_guru }}</td>
                <td>{{ $g->gender }}</td>
                <td>{{ $g->jurusan->nama_jurusan }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
