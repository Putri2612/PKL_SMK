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
use App\Models\TahunAjar;
use App\Models\Gurukelas;
use App\Models\Gurumapel;
use App\Models\Userchat;
use App\Models\Siswa;
use App\Models\Materi;
use App\Models\Notifikasi;
use App\Models\TugasSiswa;
use App\Models\WaktuUjian;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class KmMateriSiswaController extends Controller
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
        
        $km_materi = KmMateri::select('sesi_id')
            ->whereIn('sesi_id', function ($query) use ($siswa) {
                $query->select('sesi_id')
                    ->from('akses_sesi')
                    ->where('kelas_id', $siswa->kelas_id);
            })
            ->groupByRaw('sesi_id')
            ->get();
        //dd($km_materi);
        $tahun_ajar = TahunAjar::where('status', 1)->pluck('nama')->toArray();
        return view('siswa.km_materi.indexSelect', [
            'title' => 'Data Materi',
            'plugin' => '
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
                <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
                <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
            ',
            'menu' => [
                'menu' => 'km_materi',
                'expanded' => 'km_materi',
            ],
            'siswa' => $siswa,
            'km_materi' => $km_materi,
            'tahun_ajar' => $tahun_ajar,
            'notif_tugas' => $notif_tugas,
            'notif_materi' => Notifikasi::where('siswa_id', session('siswa')->id)->get(),
            'notif_ujian' => $notif_ujian,
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
        
        $km_materi = KmMateri::where('sesi_id', $kode)
            ->whereIn('sesi_id', function ($query) use ($kelas_id) {
                $query->select('sesi_id')
                    ->from('akses_sesi')
                    ->where('kelas_id', $kelas_id);
            })
            ->get();
        
       // dd($km_materi);
        $tahun_ajar = TahunAjar::where('status', 1)->pluck('nama')->toArray();
        return view('siswa.km_materi.index', [
            'title' => 'Data Materi',
            'plugin' => '
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
                <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
                <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
            ',
            'menu' => [
                'menu' => 'km_materi',
                'expanded' => 'km_materi'
            ],
            'siswa' => $siswa,
            'notif_tugas' => $notif_tugas,
            'tahun_ajar' => $tahun_ajar,
            'notif_materi' => Notifikasi::where('siswa_id', session('siswa')->id)->get(),
            'notif_ujian' => $notif_ujian,
            'km_materi' => $km_materi,
        ]);
    }

    public function show($kode)
    {
        $materi = KmMateri::where('kode', $kode)->first();
        //dd($materi);
        Notifikasi::where('siswa_id', session('siswa')->id)
            ->where('kode', $materi->kode)
            ->delete();
        
        $notif_ujian = WaktuUjian::where('siswa_id', session('siswa')->id)
            ->where('selesai', null)
            ->get();
        
        $notif_tugas = TugasSiswa::where('siswa_id', session('siswa')->id)
            ->where('date_send', null)
            ->get();

        $siswa = Siswa::firstWhere('id', session('siswa')->id);

        // $km_materi = KmMateri::where('kode', $materi->kode)->first();
        
        return view('siswa.km_materi.show', [
            'title' => 'Lihat Materi',
            'plugin' => '
                <link href="' . url("/assets/cbt-malela") . '/assets/css/components/custom-list-group.css" rel="stylesheet" type="text/css" />
                <link href="' . url("/assets/cbt-malela") . '/assets/css/components/custom-media_object.css" rel="stylesheet" type="text/css" />
            ',
            'menu' => [
                'menu' => 'km_materi',
                'expanded' => 'km_materi'
            ],
            'siswa' => $siswa,
            'km_materi'  => $materi,
            'files' => FileModel::where('kode', $materi->kode)->get(),
            'notif_tugas' => $notif_tugas,
            'notif_materi' => Notifikasi::where('siswa_id', session('siswa')->id)->get(),
            'notif_ujian' => $notif_ujian,
        ]);
    }



}
