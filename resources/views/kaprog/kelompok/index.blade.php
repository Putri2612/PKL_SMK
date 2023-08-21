@extends('template.main')
@section('content')
    @include('template.navbar.kaprog')
    <!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-lg-12 layout-spacing">
                <div class="widget shadow p-3">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="widget-heading">
                                {{-- @foreach ($tahun_ajar as $nama_tahun_ajar)
                                    <h5 class="">Kelompok Belajar - {{ $nama_tahun_ajar }}</h5>
                                @endforeach --}}
                                <h5 class="">Kelompok PKL</h5>
                            </div>
                            <div class="table-responsive mt-3">
                                <table id="datatable-table" class="table table-bordered table-striped text-center text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Tahun Ajar</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tahun_ajar as $ta)
                                            <tr>
                                                <td><?= $ta->nama ?></td> 
                                                <td>
                                                    <a href="{{ url('/kaprog/kelompok/' . $ta->id) }}"
                                                        class="btn btn-primary btn-sm"><span
                                                            data-feather="eye"></span></a>                                                               
                                                    {{-- <a href="javascript:void(0)" data-toggle="modal" data-target="#edit_kelompok_belajar" data-id="{{ $kb->id }}" data-nama_kelompok="{{ $kb->nama_kelompok }}" data-id_kelas="{{ $kb->kelas->id }}" class="btn btn-primary btn-sm edit-kelompok-belajar">
                                                        <i data-feather="edit"></i>
                                                    </a> --}}
                                                    {{-- <a href="{{ url('/admin/hapus_kelompok')}}/{{ $kb->id }}" class="btn btn-danger btn-sm btn-hapus">
                                                        <i data-feather="x-circle"></i>
                                                    </a> --}}
                                                    
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-5 d-flex">
                            <img src="{{ url('assets/img') }}/kelas.svg" class="align-middle" alt="" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('template.footer')
</div>
<!--  END CONTENT AREA  -->



{!! session('pesan') !!}
<script>
$(document).ready(function() {
    $('.btn-detail-kelompok').click(function() {
        var id_kelompok = $(this).data('id_kelompok');
        $('#detail_kelompok_modal').attr('data-id_kelompok', id_kelompok);
    });
    function getSiswaByKelas() {
        var id_kelas = document.getElementById('id_kelas').value;
        if (id_kelas) {
            // Lakukan permintaan AJAX untuk mendapatkan daftar siswa berdasarkan kelas
            var xhr = new XMLHttpRequest();
            var url = "/admin/getSiswaByKelas/" + id_kelas; // Ubah menjadi query parameter
            xhr.open('GET', url, true);

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    // Tampilkan daftar siswa
                    var siswaContainer = document.getElementById('siswaContainer');
                    siswaContainer.innerHTML = '';

                    // Buat elemen tabel
                    var table = document.createElement('table');
                    table.classList.add('table');

                    // Buat elemen tbody untuk menampung baris-baris dalam tabel
                    var tbody = document.createElement('tbody');

                    // Iterasi melalui respons JSON untuk membuat baris-baris dalam tabel
                    var response = JSON.parse(xhr.responseText);
                    response.forEach(function(siswa) {
                        // Buat baris dalam tabel
                        var row = document.createElement('tr');

                        // Buat sel-sel dalam baris untuk menampilkan data siswa
                        var idCell = document.createElement('td');
                        idCell.textContent = siswa.id;
                        var namaCell = document.createElement('td');
                        namaCell.textContent = siswa.nama_siswa;

                        // Tambahkan sel-sel ke dalam baris
                        row.appendChild(idCell);
                        row.appendChild(namaCell);

                        // Tambahkan baris ke dalam tbody
                        tbody.appendChild(row);
                    });

                    // Tambahkan tbody ke dalam tabel
                    table.appendChild(tbody);

                    // Tambahkan tabel ke dalam siswaContainer
                    siswaContainer.appendChild(table);
                }
            };
            xhr.send();
        } else {
            // Kosongkan daftar siswa jika tidak ada kelas yang dipilih
            var siswaContainer = document.getElementById('siswaContainer');
            siswaContainer.innerHTML = '';
        }
    }
    
    // Menggunakan event listener untuk memicu permintaan AJAX saat pilihan kelas berubah
    document.getElementById('id_kelas').addEventListener('change', getSiswaByKelas);
});

</script>





@endsection