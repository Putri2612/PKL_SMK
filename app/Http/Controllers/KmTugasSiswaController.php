<?php

namespace App\Http\Controllers;
use App\Models\Guru;
use App\Models\Admin;
use App\Models\Kelas;
use App\Models\KmMateri;
use App\Models\Sesi;
use App\Models\KelompokBelajar;
use App\Models\AksesSesi;
use App\Models\EmailSettings;
use App\Models\FileModel;
use App\Models\Gurukelas;
use App\Models\KmTugas;
use App\Models\TahunAjar;
use App\Models\KmTugasSiswa;
use App\Models\Gurumapel;
use App\Models\Userchat;
use App\Models\Siswa;
use App\Models\Materi;
use App\Models\Notifikasi;
use App\Models\Tugas;
use Carbon\Carbon;
use App\Models\TugasSiswa;
use App\Models\WaktuUjian;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;


class KmTugasSiswaController extends Controller
{
    public function index()
    {
        $notif_tugas = TugasSiswa::where('siswa_id', session('siswa')->id)
            ->where('date_send', null)
            ->get();
        $notif_ujian = WaktuUjian::where('siswa_id', session('siswa')->id)
            ->where('selesai', null)
            ->get();
        $siswa = Siswa::firstWhere('id', session('siswa')->id);
        $km_tugas = KmTugas::select('sesi_id')
            ->whereIn('sesi_id', function ($query) use ($siswa) {
                $query->select('sesi_id')
                    ->from('akses_sesi')
                    ->where('kelas_id', $siswa->kelas_id);
            })
            ->groupByRaw('sesi_id')
            ->get(); 
        //dd($km_tugas); 
        $tahun_ajar = TahunAjar::where('status', 1)->pluck('nama')->toArray();       
        return view('siswa.km_tugas.indexSelect', [
            'title' => 'Data Tugas',
            'plugin' => '
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
                <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
                <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
            ',
            'menu' => [
                'menu' => 'km_tugas',
                'expanded' => 'km_tugas'
            ],
            'siswa' => Siswa::firstWhere('id', session('siswa')->id),
            'km_tugas' => $km_tugas,
            'notif_tugas' => $notif_tugas,
            'tahun_ajar' => $tahun_ajar,
            'notif_materi' => Notifikasi::where('siswa_id', session('siswa')->id)->get(),
            'notif_ujian' => $notif_ujian,
       
            //'materi' => Materi::select('mapel_id')->where('kelas_id', session('siswa')->kelas_id)->groupByRaw('mapel_id')->get(),
        ]);
    }

    public function index_select($kode)
    {
        $notif_tugas = TugasSiswa::where('siswa_id', session('siswa')->id)
            ->where('date_send', null)
            ->get();

        $notif_ujian = WaktuUjian::where('siswa_id', session('siswa')->id)
            ->where('selesai', null)
            ->get();    

        $siswa = Siswa::firstWhere('id', session('siswa')->id);
        
        $kelas_id = $siswa->kelas_id;
        
        $km_tugas = KmTugas::where('sesi_id', $kode)
            ->whereIn('sesi_id', function ($query) use ($kelas_id) {
                $query->select('sesi_id')
                    ->from('akses_sesi')
                    ->where('kelas_id', $kelas_id);
            })
            ->get();
        
       //dd($km_tugas);
        $tahun_ajar = TahunAjar::where('status', 1)->pluck('nama')->toArray();
        return view('siswa.km_tugas.index', [
            'title' => 'Data Tugas',
            'plugin' => '
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
                <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
                <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
            ',
            'menu' => [
                'menu' => 'km_tugas',
                'expanded' => 'km_tugas'
            ],
            'siswa' => $siswa,
            'notif_tugas' => $notif_tugas,
            'notif_materi' => Notifikasi::where('siswa_id', session('siswa')->id)->get(),
            'notif_ujian' => $notif_ujian,
            'tahun_ajar' => $tahun_ajar,
            'km_tugas' => $km_tugas,
        ]);
    }

    public function show($kode)
    {
        $km_tugas = KmTugas::where('kode', $kode)->first();
        //dd($tugas);
        $notif_tugas = TugasSiswa::where('siswa_id', session('siswa')->id)
            ->where('date_send', null)
            ->get();

        $tugas_siswa = KmTugasSiswa::where('kode', $km_tugas->kode)
            ->where('siswa_id', session('siswa')->id)
            ->first();
        //dd($tugas_siswa);
        if ($tugas_siswa) {
            $file_siswa = FileModel::where('kode', $tugas_siswa->file)->get();
        } else {
            $file_siswa = null;
        }

        $notif_ujian = WaktuUjian::where('siswa_id', session('siswa')->id)
            ->where('selesai', null)
            ->get();

        $siswa = Siswa::firstWhere('id', session('siswa')->id);
        $kelas = $siswa->kelas;            


        return view('siswa.km_tugas.show', [
            'title' => 'Lihat Tugas',
            'plugin' => '
                <link href="' . url("/assets/cbt-malela") . '/assets/css/components/custom-list-group.css" rel="stylesheet" type="text/css" />
                <link href="' . url("/assets/cbt-malela") . '/assets/css/components/custom-media_object.css" rel="stylesheet" type="text/css" />
                <link href="' . url("/assets/cbt-malela") . '/plugins/file-upload/file-upload-with-preview.min.css" rel="stylesheet" type="text/css" />
                <script src="' . url("/assets/cbt-malela") . '/plugins/file-upload/file-upload-with-preview.min.js"></script>
            ',
            'menu' => [
                'menu' => 'km_tugas',
                'expanded' => 'km_tugas'
            ],
            'siswa' => Siswa::firstWhere('id', session('siswa')->id),
            'tugas' => $km_tugas,
            'tugas_siswa' => $tugas_siswa,
            'files' => FileModel::where('kode', $km_tugas->kode)->get(),
            'file_siswa' => $file_siswa,
            'notif_tugas' => $notif_tugas,
            'notif_materi' => Notifikasi::where('siswa_id', session('siswa')->id)->get(),
            'notif_ujian' => $notif_ujian,
        ]);
    }
    
    public function edit(Tugas $tuga)
    {
        $siswa_id = session('siswa')->id;

        $tugas_siswa = KmTugasSiswa::where('siswa_id', $siswa_id)
        ->where($tuga->kode)
        ->first();

        $notif_tugas = TugasSiswa::where('siswa_id', session('siswa')->id)
            ->where('date_send', null)
            ->get();

        // $tugas_siswa = TugasSiswa::where('kode', $tuga->kode)
        //     ->where('siswa_id', session('siswa')->id)
        //     ->first();

        $notif_ujian = WaktuUjian::where('siswa_id', session('siswa')->id)
            ->where('selesai', null)
            ->get();

        return view('siswa.km_tugas.edit', [
            'title' => 'Kerjakan Tugas',
            'plugin' => '
                <link href="' . url("/assets/cbt-malela") . '/assets/css/components/custom-list-group.css" rel="stylesheet" type="text/css" />
                <link href="' . url("/assets/cbt-malela") . '/assets/css/components/custom-media_object.css" rel="stylesheet" type="text/css" />
                <link href="' . url("/assets/cbt-malela") . '/plugins/file-upload/file-upload-with-preview.min.css" rel="stylesheet" type="text/css" />
                <script src="' . url("/assets/cbt-malela") . '/plugins/file-upload/file-upload-with-preview.min.js"></script>
            ',
            'menu' => [
                'menu' => 'km_tugas',
                'expanded' => 'km_tugas'
            ],
            'siswa' => Siswa::firstWhere('id', session('siswa')->id),
            'tugas_siswa' => $tugas_siswa,
            'file_siswa' => FileModel::where('kode', $tugas_siswa->file)->get(),
            'notif_tugas' => $notif_tugas,
            'notif_materi' => Notifikasi::where('siswa_id', session('siswa')->id)->get(),
            'notif_ujian' => $notif_ujian
        ]);
    }

    
    public function update(Request $request, Tugas $tugas)
    {
        $siswa_id = session('siswa')->id;

        $tugas_siswa = KmTugasSiswa::where('siswa_id', $siswa_id)
            ->where($tugas->kode)
            ->first();

        if ($tugas_siswa == null) {
            // Jika tidak ada data yang ditemukan, buat data baru
            $tugas_siswa = new KmTugasSiswa();
            $tugas_siswa->kode = $tugas->kode;
            $tugas_siswa->siswa_id = $siswa_id;
        }

        if ($tugas_siswa->file == null) {
            $kode_file = Str::random(20);
        } else {
            $kode_file = $tugas_siswa->file;
        }

        $tugas_siswa->teks = $request->teks;
        $tugas_siswa->file = $kode_file;
        $tugas_siswa->date_send = Carbon::now(); // Menggunakan tanggal dan waktu saat ini
        $tugas_siswa->is_telat = strtotime($tugas_siswa->date_send) > strtotime($tugas->due_date) ? 1 : 0;


        $tugas_siswa->save();

        $kelompok_siswa_id = $tugas_siswa->kelompok_id;
        //dd($kelompok_siswa_id);

        $kelompok_siswa = KmTugasSiswa::where('kelompok_id', $kelompok_siswa_id)
            ->where($tugas->kode)
            ->get();
        //dd($kelompok_siswa);
        if ($kelompok_siswa != null) {
            foreach ($kelompok_siswa as $siswa) {
                $siswa->teks = $request->teks;
                $siswa->file = $kode_file;
                $siswa->date_send = Carbon::now(); // Menggunakan tanggal dan waktu saat ini
                $tugas_siswa->is_telat = strtotime($tugas_siswa->date_send) > strtotime($tugas->due_date) ? 1 : 0;
                $siswa->save();
            }
        }

        if ($request->file('files')) {
            $files = [];
            foreach ($request->file('files') as $file) {
                array_push($files, [
                    'kode' => $tugas_siswa->file,
                    'nama' => Str::replace('assets/files/', '', $file->store('assets/files'))
                ]);
            }
            FileModel::insert($files);
        }

        return redirect('/siswa/km_tugas/' . $tugas->kode)->with('pesan', "
            <script>
            swal({
                title: 'Success!',
                text: 'Tugas sudah dikerjakan!',
                type: 'success',
                padding: '2em'
            });
            </script>
        ");
    }

    public function kerjakan(KmTugasSiswa $tugas_siswa)
    {
        //dd($tugas_siswa);
        $notif_tugas = TugasSiswa::where('siswa_id', session('siswa')->id)
            ->where('date_send', null)
            ->get();

        $notif_ujian = WaktuUjian::where('siswa_id', session('siswa')->id)
            ->where('selesai', null)
            ->get();

        return view('siswa.km_tugas.kerjakan', [
            'title' => 'Kerjakan Tugas',
            'plugin' => '
                <link href="' . url("/assets/cbt-malela") . '/assets/css/components/custom-list-group.css" rel="stylesheet" type="text/css" />
                <link href="' . url("/assets/cbt-malela") . '/assets/css/components/custom-media_object.css" rel="stylesheet" type="text/css" />
                <link href="' . url("/assets/cbt-malela") . '/plugins/file-upload/file-upload-with-preview.min.css" rel="stylesheet" type="text/css" />
                <script src="' . url("/assets/cbt-malela") . '/plugins/file-upload/file-upload-with-preview.min.js"></script>
            ',
            'menu' => [
                'menu' => 'km_tugas',
                'expanded' => 'km_tugas'
            ],
            'siswa' => Siswa::firstWhere('id', session('siswa')->id),
            'tugas_siswa' => $tugas_siswa,
            'notif_tugas' => $notif_tugas,
            'notif_materi' => Notifikasi::where('siswa_id', session('siswa')->id)->get(),
            'notif_ujian' => $notif_ujian
        ]);
        
    }



   
}
