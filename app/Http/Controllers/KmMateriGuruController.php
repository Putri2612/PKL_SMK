<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Admin;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\KmMateri;
use App\Models\TahunAjar;
use App\Models\Sesi;
use App\Models\KelompokBelajar;
use App\Models\AksesSesi;
use App\Models\EmailSettings;
use App\Models\FileModel;
use App\Models\Gurukelas;
use App\Models\Gurumapel;
use App\Models\Userchat;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class KmMateriGuruController extends Controller
{
    public function index()
    {
        
        $tahun_ajar = TahunAjar::where('status', 1)->pluck('nama')->toArray();
        return view('guru.km_materi.indexSelect', [
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
            //'km_materi' => KmMateri::all(),
            'guru' => Guru::firstWhere('id', session('guru')->id),
            'tahun_ajar' => $tahun_ajar,
            //'guru_mapel' => Gurumapel::where('guru_id', session('guru')->id)->get(),
            'km_materi' => KmMateri::select('sesi_id')->where('guru_id', session('guru')->id)->groupByRaw('sesi_id')->get(),
            //'sesi' => Sesi::all()
        ]);
    }

    public function index_select($kode)
    {
        $tahun_ajar = TahunAjar::where('status', 1)->pluck('nama')->toArray();
        return view('guru.km_materi.index', [
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
            'guru' => Guru::firstWhere('id', session('guru')->id),
            'tahun_ajar' => $tahun_ajar,
            'km_materi' => KmMateri::where('sesi_id', $kode)->get()
        ]);
    }

    public function show($kode)
    {
        $materi = KmMateri::where('kode', $kode)->first();
        //dd($materi);
        return view('guru.km_materi.show', [
            'title' => 'Lihat Materi',
            'plugin' => '
                <link href="' . url("/assets/cbt-malela") . '/assets/css/components/custom-list-group.css" rel="stylesheet" type="text/css" />
                <link href="' . url("/assets/cbt-malela") . '/assets/css/components/custom-media_object.css" rel="stylesheet" type="text/css" />
            ',
            'menu' => [
                'menu' => 'km_materi',
                'expanded' => 'km_materi'
            ],
            'guru' => Guru::firstWhere('id', session('guru')->id),
            'km_materi'  => $materi,
            'files' => FileModel::where('kode', $materi->kode)->get()
        ]);
    }
}
