<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\DuDiController;
use App\Http\Controllers\KaprogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KelompokController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\TugasGuruController;
use App\Http\Controllers\UjianGuruController;
use App\Http\Controllers\MateriGuruController;
use App\Http\Controllers\KmMateriGuruController;
use App\Http\Controllers\KmTugasGuruController;
use App\Http\Controllers\KmMateriSiswaController;
use App\Http\Controllers\KmTugasSiswaController;
use App\Http\Controllers\MateriAdminController;
use App\Http\Controllers\TugasAdminController;
use App\Http\Controllers\SummernoteController;
use App\Http\Controllers\TugasSiswaController;
use App\Http\Controllers\MateriSiswaController;
use App\Http\Controllers\UjianSiswaController;
use App\Models\DuDi;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route Auth
// ==>View
Route::get('/', [AuthController::class, 'index']);
Route::get('/install', [AuthController::class, 'install']);
Route::get('/register', [AuthController::class, 'register']);
Route::get('/recovery', [AuthController::class, 'recovery']);
Route::get('/change_password/{token:token}', [AuthController::class, 'change_password']);
Route::get('/logout', [AuthController::class, 'logout']);
// ==>Function
Route::post('/login', [AuthController::class, 'login']);
Route::post('/install', [AuthController::class, 'install_']);
Route::post('/register', [AuthController::class, 'register_']);
Route::post('/recovery', [AuthController::class, 'recovery_']);
Route::get('/aktivasi/{token:token}', [AuthController::class, 'aktivasi']);
Route::post('/change_password/{token:token}', [AuthController::class, 'change_password_']);


// START::ROUTE ADMIN
Route::get('/admin', [AdminController::class, 'index'])->middleware('is_admin');
Route::get('/admin/profile', [AdminController::class, 'profile'])->middleware('is_admin');
Route::post('/admin/edit_profile/{admin:id}', [AdminController::class, 'edit_profile'])->middleware('is_admin');
Route::post('/admin/edit_password/{admin:id}', [AdminController::class, 'edit_password'])->middleware('is_admin');
Route::post('/admin/smtp_email/{id}', [AdminController::class, 'smtp_email'])->middleware('is_admin');
// =============SISWA
// ==>View
Route::get('/admin/siswa', [AdminController::class, 'siswa'])->middleware('is_admin');
Route::get('/admin/edit_siswa', [AdminController::class, 'edit_siswa'])->name('ajaxsiswa')->middleware('is_admin');
Route::get('/admin/impor_siswa', [AdminController::class, 'impor_siswa'])->middleware('is_admin');
Route::get('/admin/ekspor_siswa', [AdminController::class, 'ekspor_siswa'])->middleware('is_admin');

// ==>Function
Route::post('/admin/tambah_siswa', [AdminController::class, 'tambah_siswa_'])->middleware('is_admin');
Route::post('/admin/edit_siswa', [AdminController::class, 'edit_siswa_'])->middleware('is_admin');
Route::post('/admin/impor_siswa', [AdminController::class, 'impor_siswa_'])->middleware('is_admin');
Route::get('/admin/hapus_siswa/{siswa:nisn}', [AdminController::class, 'hapus_siswa'])->middleware('is_admin');

// ============GURU
// ==>View
Route::get('/admin/guru', [AdminController::class, 'guru'])->middleware('is_admin');
Route::get('/admin/edit_guru', [AdminController::class, 'edit_guru'])->name('ajaxguru')->middleware('is_admin');
Route::get('/admin/impor_guru', [AdminController::class, 'impor_guru'])->middleware('is_admin');
Route::get('/admin/ekspor_guru', [AdminController::class, 'ekspor_guru'])->middleware('is_admin');

// ==>Function
Route::post('/admin/tambah_guru', [AdminController::class, 'tambah_guru_'])->middleware('is_admin');
Route::post('/admin/edit_guru', [AdminController::class, 'edit_guru_'])->middleware('is_admin');
Route::post('/admin/impor_guru', [AdminController::class, 'impor_guru_'])->middleware('is_admin');
Route::get('/admin/hapus_guru/{guru:nip}', [AdminController::class, 'hapus_guru'])->middleware('is_admin');

//==========DUDI
Route::get('/admin/dudi', [AdminController::class, 'dudi'])->middleware('is_admin');

//==========Peserta PKL
Route::get('/admin/peserta', [AdminController::class, 'peserta'])->middleware('is_admin');
Route::get('/admin/peserta_pkl/{id}', [AdminController::class, 'pesertaSelect'])->middleware('is_admin');
Route::get('/admin/peserta_pkl/surat_balasan/{id}', [AdminController::class, 'lihatSuratBalasan'])->middleware('is_admin');


// ============TahunAjar
// ==>View
Route::get('/admin/tahun_ajar', [AdminController::class, 'tahun_ajar'])->middleware('is_admin');
// ==>Function
Route::post('/admin/tambah_tahun_ajar', [AdminController::class, 'tambah_tahun_ajar'])->middleware('is_admin');
Route::post('/admin/edit_tahun', [AdminController::class, 'edit_tahun'])->middleware('is_admin');
Route::get('/admin/hapus_tahun_ajar/{tahun_ajar:id}', [AdminController::class, 'hapus_tahun_ajar'])->middleware('is_admin');

// ============KELAS
// ==>View
Route::get('/admin/kelas', [AdminController::class, 'kelas'])->middleware('is_admin');
// ==>Function
Route::post('/admin/tambah_kelas', [AdminController::class, 'tambah_kelas'])->middleware('is_admin');
Route::post('/admin/edit_kelas', [AdminController::class, 'edit_kelas'])->middleware('is_admin');
Route::get('/admin/hapus_kelas/{kelas:id}', [AdminController::class, 'hapus_kelas'])->middleware('is_admin');

// ============MAPEL
// ==>View
Route::get('/admin/mapel', [AdminController::class, 'mapel'])->middleware('is_admin');
// ==>Function
Route::post('/admin/tambah_mapel', [AdminController::class, 'tambah_mapel'])->middleware('is_admin');
Route::post('/admin/edit_mapel', [AdminController::class, 'edit_mapel'])->middleware('is_admin');
Route::get('/admin/hapus_mapel/{mapel:id}', [AdminController::class, 'hapus_mapel'])->middleware('is_admin');

// ============RELASI
// ==>View
Route::get('/admin/relasi', [AdminController::class, 'relasi'])->middleware('is_admin');
Route::get('/admin/relasi_guru/{guru:id}', [AdminController::class, 'relasi_guru'])->middleware('is_admin');
// ==>Function
Route::get('/admin/guru_kelas', [AdminController::class, 'guru_kelas'])->name('guru_kelas')->middleware('is_admin');
Route::get('/admin/guru_mapel', [AdminController::class, 'guru_mapel'])->name('guru_mapel')->middleware('is_admin');


// ============KELOMPOK BELAJAR
// ==>View
Route::get('/admin/kelompok', [AdminController::class, 'kelompok'])->middleware('is_admin');
Route::get('/admin/kelompok/{id}', [AdminController::class, 'showKelompok'])->middleware('is_admin');


// ==>Function
Route::post('/admin/tambahkelompok', [AdminController::class, 'tambahkelompok'])->middleware('is_admin');
//Route::post('/admin/edit_kelompok', [AdminController::class, 'edit_kelompok'])->middleware('is_admin');
Route::get('/admin/hapus_kelompok/{kelompok_belajar:id}', [AdminController::class, 'hapus_kelompok'])->middleware('is_admin');
Route::get('/admin/getSiswaByKelas/{id_kelas}', [AdminController::class, 'getSiswaByKelas']);

//==>Logbook
Route::get('/admin/logbook', [AdminController::class, 'logbook'])->middleware('is_admin');
Route::get('/admin/logbook/{id}', [AdminController::class, 'logbookSelect'])->middleware('is_admin');
//==>Monitoring
Route::get('/admin/monitoring', [AdminController::class, 'monitoring'])->middleware('is_admin');
Route::get('/admin/monitoring/{id}', [AdminController::class, 'monitoringSelect'])->middleware('is_admin');
//==>Kunjungan
Route::get('/admin/kunjungan', [AdminController::class, 'kunjungan'])->middleware('is_admin');
Route::get('/admin/edit_kunjungan', [AdminController::class, 'edit_kunjungan'])->name('ajaxkunjungan')->middleware('is_admin');
Route::post('/admin/edit_kunjungan', [AdminController::class, 'edit_kunjungan_'])->middleware('is_admin');
//==>Catatan DU/DI
Route::get('/admin/catatan', [AdminController::class, 'catatan'])->middleware('is_admin');
//==>Nilai
Route::get('/admin/nilai', [AdminController::class, 'nilai'])->middleware('is_admin');
Route::get('/admin/show_nilai/{nisn}', [AdminController::class, 'showNilai'])->middleware('is_admin');

Route::get('/admin/sesi_belajar', [AdminController::class, 'sesi_belajar'])->middleware('is_admin');
Route::post('/admin/tambah_sesi', [AdminController::class, 'tambah_sesi'])->middleware('is_admin');
Route::post('/admin/edit_sesi', [AdminController::class, 'edit_sesi'])->middleware('is_admin');
Route::get('/admin/hapus_sesi', [AdminController::class, 'hapus_sesi'])->middleware('is_admin');
Route::get('/admin/relasi_sesi/{sesi:id}', [AdminController::class, 'relasi_sesi'])->middleware('is_admin');
Route::get('/admin/akses_sesi', [AdminController::class, 'akses_sesi'])->name('akses_sesi')->middleware('is_admin');
//===>Materi+Tugas
Route::get('/admin/materi', [MateriAdminController::class, 'index'])->middleware('is_admin');
Route::get('/admin/materi_select/{kode}', [MateriAdminController::class, 'index_select'])->middleware('is_admin');
Route::resource('/admin/materi', MateriAdminController::class)->middleware('is_admin');
Route::get('/admin/tugas', [TugasAdminController::class, 'index'])->middleware('is_admin');
Route::get('/admin/tugas_select/{kode}', [TugasAdminController::class, 'index_select'])->middleware('is_admin');
Route::resource('/admin/tugas', TugasAdminController::class)->middleware('is_admin');


// END::ROUTE ADMIN



// SUMMERNOTE
Route::post('/summernote/upload', [SummernoteController::class, 'upload'])->name('summernote_upload');
Route::post('/summernote/delete', [SummernoteController::class, 'delete'])->name('summernote_delete');
Route::get('/summernote/unduh/{file}', [SummernoteController::class, 'unduh']);
Route::post('/summernote/delete_file', [SummernoteController::class, 'delete_file']);

// START::ROUTE GURU
Route::get('/guru', [GuruController::class, 'index'])->middleware('is_guru');
Route::get('/guru/profile', [GuruController::class, 'profile'])->middleware('is_guru');
Route::post('/guru/edit_profile/{guru:nip}', [GuruController::class, 'edit_profile'])->middleware('is_guru');
Route::post('/guru/edit_password/{guru:nip}', [GuruController::class, 'edit_password'])->middleware('is_guru');

// ==>Materi
Route::post('/guru/edit_password/{guru:id}', [GuruController::class, 'edit_password'])->middleware('is_guru');

//==> Kelompok
Route::get('/guru/kelompok', [GuruController::class, 'kelompok'])->middleware('is_guru');
//==>Logbook
Route::get('/guru/logbook', [GuruController::class, 'logbook'])->middleware('is_guru');
//==>Monitoring
Route::get('/guru/monitoring', [GuruController::class, 'monitoring'])->middleware('is_guru');
Route::post('/guru/tambah_monitoring', [GuruController::class, 'tambah_monitoring'])->middleware('is_guru');
Route::get('/guru/edit_monitoring', [GuruController::class, 'edit_monitoring'])->name('ajaxmonitoring')->middleware('is_guru');
Route::post('/guru/edit_monitoring', [GuruController::class, 'edit_monitoring_'])->middleware('is_guru');
Route::get('/guru/hapus_monitoring/{monitoring:id}', [GuruController::class, 'hapus_monitoring'])->middleware('is_guru');
//==>Kegiatan Kunjungan
Route::get('/guru/kunjungan', [GuruController::class, 'kunjungan'])->middleware('is_guru');
Route::post('/guru/tambah_kunjungan', [GuruController::class, 'tambah_kunjungan'])->middleware('is_guru');
Route::get('/guru/edit_kunjungan', [GuruController::class, 'edit_kunjungan'])->name('ajaxkunjungan')->middleware('is_guru');
Route::post('/guru/edit_kunjungan', [GuruController::class, 'edit_kunjungan_'])->middleware('is_guru');
Route::get('/guru/hapus_kunjungan/{kunjungan:id}', [GuruController::class, 'hapus_kunjungan'])->middleware('is_guru');
//==>Catatan DU/DI
Route::get('/guru/catatan', [GuruController::class, 'catatan'])->middleware('is_guru');
//==>Nilai
Route::get('/guru/nilai', [GuruController::class, 'nilai'])->middleware('is_guru');
Route::get('/guru/show_nilai/{siswa_nisn}', [GuruController::class, 'showNilai'])->middleware('is_guru');

// END ROUTE GURU

// START::ROUTE KAPROG
Route::get('/kaprog', [KaprogController::class, 'index'])->middleware('is_kaprog');
Route::get('/kaprog/profile', [KaprogController::class, 'profile'])->middleware('is_kaprog');
Route::post('/kaprog/edit_profile/{guru:nip}', [KaprogController::class, 'edit_profile'])->middleware('is_kaprog');
Route::post('/kaprog/edit_password/{guru:nip}', [KaprogController::class, 'edit_password'])->middleware('is_kaprog');
//==>INFO PKL
Route::get('/kaprog/dudi', [KaprogController::class, 'dudi'])->middleware('is_kaprog');
Route::get('/kaprog/edit_dudi', [KaprogController::class, 'edit_dudi'])->name('ajaxdudi')->middleware('is_kaprog');
Route::get('/kaprog/impor_dudi', [KaprogController::class, 'impor_dudi'])->middleware('is_kaprog');
Route::get('/kaprog/ekspor_dudi', [KaprogController::class, 'ekspor_dudi'])->middleware('is_kaprog');

// ==>Function
Route::post('/kaprog/tambah_dudi', [KaprogController::class, 'tambah_dudi'])->middleware('is_kaprog');
Route::post('/kaprog/edit_dudi', [KaprogController::class, 'edit_dudi_'])->middleware('is_kaprog');
Route::post('/kaprog/impor_dudi', [KaprogController::class, 'impor_dudi_'])->middleware('is_kaprog');
Route::get('/kaprog/hapus_dudi/{dudi:id}', [KaprogController::class, 'hapus_dudi'])->middleware('is_kaprog');

// ==>Daftar Peserta PKL
Route::get('/kaprog/peserta', [KaprogController::class, 'peserta'])->middleware('is_kaprog');
Route::get('/kaprog/kelompok', [KaprogController::class, 'kelompok'])->middleware('is_kaprog');
Route::get('/kaprog/kelompok/{id}', [KaprogController::class, 'showKelompok'])->middleware('is_kaprog');
Route::get('/kaprog/edit_kelompok', [KaprogController::class, 'edit_kelompok'])->name('ajaxkelompok')->middleware('is_kaprog');
Route::post('/kaprog/edit_kelompok', [KaprogController::class, 'edit_kelompok_'])->middleware('is_kaprog');
// Route::get('/kaprog/peserta_pkl/{id}', [KaprogController::class, 'pesertaSelect'])->middleware('is_kaprog');

//==>Logbook
Route::get('/kaprog/logbook', [KaprogController::class, 'logbook'])->middleware('is_kaprog');
//==>Monitoring
Route::get('/kaprog/monitoring', [KaprogController::class, 'monitoring'])->middleware('is_kaprog');
Route::post('/kaprog/tambah_monitoring', [KaprogController::class, 'tambah_monitoring'])->middleware('is_kaprog');
Route::get('/kaprog/edit_monitoring', [KaprogController::class, 'edit_monitoring'])->name('ajaxmonitoring')->middleware('is_kaprog');
Route::post('/kaprog/edit_monitoring', [KaprogController::class, 'edit_monitoring_'])->middleware('is_kaprog');
Route::get('/kaprog/hapus_monitoring/{monitoring:id}', [KaprogController::class, 'hapus_monitoring'])->middleware('is_kaprog');
//==>Kegiatan Kunjungan
Route::get('/kaprog/kunjungan', [KaprogController::class, 'kunjungan'])->middleware('is_kaprog');
Route::post('/kaprog/tambah_kunjungan', [KaprogController::class, 'tambah_kunjungan'])->middleware('is_kaprog');
Route::get('/kaprog/edit_kunjungan', [KaprogController::class, 'edit_kunjungan'])->name('ajaxkunjungan')->middleware('is_kaprog');
Route::post('/kaprog/edit_kunjungan', [KaprogController::class, 'edit_kunjungan_'])->middleware('is_kaprog');
Route::get('/kaprog/hapus_kunjungan/{kunjungan:id}', [KaprogController::class, 'hapus_kunjungan'])->middleware('is_kaprog');
//==>Catatan DU/DI
Route::get('/kaprog/catatan', [KaprogController::class, 'catatan'])->middleware('is_kaprog');
//==>Nilai
Route::get('/kaprog/nilai', [KaprogController::class, 'nilai'])->middleware('is_kaprog');
Route::get('/kaprog/show_nilai/{nisn}', [KaprogController::class, 'showNilai'])->middleware('is_kaprog');

// START ;; CHAT CONTROLLER
Route::post('/chat/ambil/{key}', [ChatController::class, 'ambil']);
Route::post('/chat/simpan/{key}', [ChatController::class, 'simpan']);
// END :: CHAT CONTROLLER

// START::ROUTE SISWA
Route::get('/siswa', [SiswaController::class, 'index'])->middleware('is_siswa');

Route::get('/siswa/profile', [SiswaController::class, 'profile'])->middleware('is_siswa');
Route::post('/siswa/edit_profile/{siswa:nisn}', [SiswaController::class, 'edit_profile'])->middleware('is_siswa');
Route::post('/siswa/edit_password/{siswa:nisn}', [SiswaController::class, 'edit_password'])->middleware('is_siswa');

// ==>Info PKL
Route::get('/siswa/dudi', [SiswaController::class, 'dudi'])->middleware('is_siswa');
Route::get('/download/{filename}', [SuratController::class, 'downloadFile'])->name('download.file');

//==>Pendaftaran PKL
Route::get('/siswa/daftar', [SiswaController::class, 'daftar'])->middleware('is_siswa');
Route::post('/siswa/daftar_pkl', [SiswaController::class, 'saveDaftarPkl'])->middleware('is_siswa');

//==>Logbook
Route::get('/siswa/logbook', [SiswaController::class, 'logbook'])->middleware('is_siswa');
Route::post('/siswa/tambah_logbook', [SiswaController::class, 'tambah_logbook'])->middleware('is_siswa');
Route::get('/siswa/edit_logbook', [SiswaController::class, 'edit_logbook'])->name('ajaxlogbook')->middleware('is_siswa');
Route::post('/siswa/edit_logbook', [SiswaController::class, 'edit_logbook_'])->middleware('is_siswa');

//==>Monitoring
Route::get('/siswa/monitoring', [SiswaController::class, 'monitoring'])->middleware('is_siswa');
//==>Catatan DU/DI
Route::get('/siswa/catatan', [SiswaController::class, 'catatan'])->middleware('is_siswa');
//==>Nilai
Route::get('/siswa/nilai', [SiswaController::class, 'nilai'])->middleware('is_siswa');

// START::ROUTE DUDI
Route::get('/dudi', [DuDiController::class, 'index'])->middleware('is_dudi');
Route::get('/dudi/profile', [DuDiController::class, 'profile'])->middleware('is_dudi');
Route::post('/dudi/edit_profile/{dudi:id}', [DuDiController::class, 'edit_profile'])->middleware('is_dudi');
Route::post('/dudi/edit_password/{dudi:id}', [DuDiController::class, 'edit_password'])->middleware('is_dudi');
//==> Kelompok
Route::get('/dudi/kelompok', [DuDiController::class, 'kelompok'])->middleware('is_dudi');
//==>Logbook
Route::get('/dudi/logbook', [DuDiController::class, 'logbook'])->middleware('is_dudi');
Route::get('/dudi/edit_logbook', [DuDiController::class, 'edit_logbook'])->name('ajaxlogbook')->middleware('is_dudi');
Route::post('/dudi/edit_logbook', [DuDiController::class, 'edit_logbook_'])->middleware('is_dudi');
//==>Catatan DU/DI
Route::get('/dudi/catatan', [DuDiController::class, 'catatan'])->middleware('is_dudi');
Route::post('/dudi/tambah_catatan', [DuDiController::class, 'tambah_catatan'])->middleware('is_dudi');
Route::get('/dudi/edit_catatan', [DuDiController::class, 'edit_catatan'])->name('ajaxcatatandudi')->middleware('is_dudi');
Route::post('/dudi/edit_catatan', [DuDiController::class, 'edit_catatan_'])->middleware('is_dudi');
Route::get('/dudi/hapus_catatan/{catatan_dudi:id}', [DuDiController::class, 'hapus_catatan'])->middleware('is_dudi');
//==>Nilai
Route::get('/dudi/nilai', [DuDiController::class, 'nilai'])->middleware('is_dudi');
Route::get('/dudi/nilai/{siswa_nisn}', [DuDiController::class, 'nilaiSelect'])->middleware('is_dudi');
Route::get('/dudi/show_nilai/{siswa_nisn}', [DuDiController::class, 'showNilai'])->middleware('is_dudi');
Route::post('/dudi/tambah_nilai', [DuDiController::class, 'simpanNilai'])->middleware('is_dudi');