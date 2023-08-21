<table>
    <thead>
        <tr>
            <th>No</th>
            <th>NISN</th>
            <th>Nama Siswa</th>
            <th>Gender</th>
            <th>Kelas</th>
            <th>Jurusan</th>
            <th>No Telp</th>
            <th>Alamat</th>
            <th>Nama Ayah</th>
            <th>Nama Ibu</th>
            <th>Alamat Wali</th>
            <th>Pekerjaan Wali</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($siswa as $s)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $s->nisn }}</td>
                <td>{{ $s->nama_siswa }}</td>
                <td>{{ $s->gender }}</td>
                <td>{{ $s->kelas->nama_kelas }}</td>
                <td>{{ $s->jurusan->nama_jurusan }}</td>
                <td>{{ $s->no_telp }}</td>
                <td>{{ $s->alamat }}</td>
                <td>{{ $s->nama_ayah }}</td>
                <td>{{ $s->nama_ibu }}</td>
                <td>{{ $s->alamat_wali }}</td>
                <td>{{ $s->pekerjaan_wali }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
