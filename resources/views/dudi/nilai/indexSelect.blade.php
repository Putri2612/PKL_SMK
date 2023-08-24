@extends('template.main')
@section('content')
@include('template.navbar.dudi')

<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-lg-12 layout-spacing">
                <div class="widget shadow p-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="widget-heading ">
                                <h5 class="" style="text-align: center">Nilai Dan Sertifikat</h5>
                                <h6>Nama Siswa   : {{ $siswa->nama_siswa }}</h6>
                                <h6>NISN : {{ $siswa->nisn }}</h6>
                                <h6>DUDI : {{ $dudisiswa->nama_dudi }}</h6>
                            </div>
                            
                            <div class="table-responsive mt-4">
                                <form action="{{ url("dudi/tambah_nilai") }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="siswa_nisn[]" value="{{ $siswa->nisn }}">
                                        <table id="datatable-table" class="table text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Aspek yang Dinilai</th>
                                                    <th>Nilai</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>A.</td>
                                                    <td colspan="3">Afektif</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>Disiplin</td>
                                                    <td>
                                                        <input type="hidden" name="aspek[]" value="Disiplin">
                                                        <input type="number" name="nilai_angka[]" class="form-control" required>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>Keaktifan</td>
                                                    <td>
                                                        <input type="hidden" name="aspek[]" value="Keaktifan">
                                                        <input type="number" name="nilai_angka[]" class="form-control" required>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>Sopan Santun</td>
                                                    <td>
                                                        <input type="hidden" name="aspek[]" value="Sopan Santun">
                                                        <input type="number" name="nilai_angka[]" class="form-control" required>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>B.</td>
                                                    <td colspan="3">Produktif (Kompetensi Keahlian)</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>
                                                        <input type="text" name="aspek[]" class="form-control" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="nilai_angka[]" class="form-control" required>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary add-row"><i data-feather="plus"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                                </form>
                            </div>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('template.footer')
</div>
<!--  END CONTENT AREA  -->

<!-- END MODAL -->

{!! session('pesan'); !!}

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const addRowButton = document.querySelector(".add-row");
        const tableBody = document.querySelector("#datatable-table tbody");

        addRowButton.addEventListener("click", function () {
            const newRow = document.createElement("tr");
            newRow.innerHTML = `
                <td></td>
                <td>
                    <input type="text" name="aspek[]" class="form-control" required>
                </td>
                <td>
                    <input type="number" name="nilai_angka[]" class="form-control">
                </td>
                <td>
                    <button type="button" class="btn btn-danger remove-row">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    </button>
                </td>
            `;
            tableBody.appendChild(newRow);
        });

        tableBody.addEventListener("click", function (event) {
            if (event.target.classList.contains("remove-row")) {
                event.target.closest("tr").remove();
            }
        });
    });

    $(document).ready(function() {
        $("#datatable-table").DataTable({scrollY:"300px",scrollX:!0,scrollCollapse:!0,paging:!0,oLanguage:{oPaginate:{sPrevious:'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',sNext:'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'},sInfo:"tampilkan halaman _PAGE_ dari _PAGES_",sSearch:'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',sSearchPlaceholder:"Cari Data...",sLengthMenu:"Hasil :  _MENU_"},stripeClasses:[],lengthMenu:[[-1,5,10,25,50],["All",5,10,25,50]]});        
        $(".custom-file-input").on("change",function(){var a=$(this).val().split("\\").pop();$(this).next(".custom-file-label").addClass("selected").html(a)});
    });
</script>


@endsection