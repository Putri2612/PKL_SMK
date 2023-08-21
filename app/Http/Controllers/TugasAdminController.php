<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Admin;
use App\Models\Siswa;
use App\Models\KmTugas;
use App\Models\Sesi;
use App\Models\KelompokBelajar;
use App\Models\KelompokBelajarSiswa;
use App\Models\AksesSesi;
use App\Mail\NotifTugas;
use App\Models\Userchat;
use App\Models\FileModel;
use App\Models\TahunAjar;
use App\Models\Gurukelas;
use App\Models\Gurumapel;
use App\Models\TugasSiswa;
use App\Models\KmTugasSiswa;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmailSettings;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TugasAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tahun_ajar_aktif = TahunAjar::where('status', 1)->pluck('id')->toArray();
        return view('admin.tugas.indexSelect', [
            'title' => 'Data Tugas',
            'plugin' => '
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
                <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
                <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
            ',
            'menu' => [
                'menu' => 'tugas',
                'expanded' => 'tugas',
                'collapse' => '',
                'sub' => '',
            ],
            'admin' => Admin::firstWhere('id', session('admin')->id),
            'sesi' => Sesi::whereIn('tahun_ajar_id', $tahun_ajar_aktif)->get(),
            //'km_tugas' => KmTugas::select('mapel_id')->where('guru_id', session('guru')->id)->groupByRaw('mapel_id')->get()
        ]);
    }
    
     public function index_select($kode)
    {
        return view('admin.tugas.index', [
            'title' => 'Data Tugas',
            'plugin' => '
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
                <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
                <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
            ',
            'menu' => [
                'menu' => 'tugas',
                'expanded' => 'tugas',
                'collapse' => '',
                'sub' => '',
            ],
            'admin' => Admin::firstWhere('id', session('admin')->id),
            'km_tugas' => KmTugas::where('sesi_id',  $kode)->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tahun_ajar_aktif = TahunAjar::where('status', 1)->pluck('id')->toArray();
        return view('admin.tugas.create', [
            'title' => 'Tambah Tugas',
            'plugin' => '
                <link href="' . url("/assets/cbt-malela") . '/plugins/file-upload/file-upload-with-preview.min.css" rel="stylesheet" type="text/css" />
                <script src="' . url("/assets/cbt-malela") . '/plugins/file-upload/file-upload-with-preview.min.js"></script>
                <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
                <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
            ',
            'menu' => [
                'menu' => 'tugas',
                'expanded' => 'tugas',
                'collapse' => '',
                'sub' => '',
            ],
            'admin' => Admin::firstWhere('id', session('admin')->id),
            'sesi' => Sesi::whereIn('tahun_ajar_id', $tahun_ajar_aktif)->get(),
            'akses_sesi' => AksesSesi::all(),
        ]);
    }

//     /**
//      * Store a newly created resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @return \Illuminate\Http\Response
//      */
    public function store(Request $request)
    {
        $sesi = Sesi::find($request->sesi_id); // Mengambil data sesi berdasarkan id_sesi dari inputan
        $akses_sesi = AksesSesi::where('sesi_id', $sesi->id)->first(); // Mendapatkan akses_sesi berdasarkan id_ses
        //dd($akses_sesi);
        $kelompok_belajar = KelompokBelajar::where('id_kelas', $akses_sesi->kelas_id)->first();

        $siswa = Siswa::where('kelas_id', $akses_sesi->kelas_id)->get();

        if ($siswa->count() == 0) {
            return redirect('/admin/tugas/create')->with('pesan', "
                <script>
                    swal({
                        title: 'Error!',
                        text: 'belum ada siswa di kelas tersebut!',
                        type: 'error',
                        padding: '2em'
                    })
                </script>
            ")->withInput();
        }

        $validateTugas = $request->validate([
            'nama_tugas' => 'required',
            'teks' => 'required',
        ]);
        $validateTugas['kode'] = Str::random(20);
        $validateTugas['guru_id'] = $kelompok_belajar->id_guru;
        $validateTugas['kelas_id'] = $akses_sesi->kelas_id;
        $validateTugas['sesi_id'] = $request->sesi_id;
        $validateTugas['due_date'] = $request->tgl . ' ' . $request->jam;

        $tugas_siswa = [];
        foreach ($siswa as $s) {
            $kelompok_siswa = KelompokBelajarSiswa::where('siswa_id', $s->id)->first();
            if ($kelompok_siswa) {
                array_push($tugas_siswa, [
                    'kode' => $validateTugas['kode'],
                    'siswa_id' => $s->id,
                    'kelompok_id' => $kelompok_siswa->kelompok_id
                ]);
            }
        }
        //dd($tugas_siswa);
        
        if ($request->file('file_tugas')) {
            $files = [];
            foreach ($request->file('file_tugas') as $file) {
                array_push($files, [
                    'kode' => $validateTugas['kode'],
                    'nama' => Str::replace('assets/files/', '', $file->store('assets/files'))
                ]);
            }
            FileModel::insert($files);
        }

        KmTugas::create($validateTugas);
        KmTugasSiswa::insert($tugas_siswa);

        return redirect('/admin/tugas')->with('pesan', "
            <script>
                swal({
                    title: 'Success!',
                    text: 'tugas sudah di posting!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }


//     /**
//      * Display the specified resource.
//      *
//      * @param  \App\Models\Tugas  $tugas
//      * @return \Illuminate\Http\Response
//      */
    public function show($kode)
    {
    $tugas = KmTugas::where('kode', $kode)->first();

    $sesiId = $tugas->sesi_id; // Ambil id sesi dari KmTugas

    // Dapatkan data kelas yang terkait dengan sesi
    $kelasIds = AksesSesi::where('sesi_id', $sesiId)->pluck('kelas_id')->toArray();

    // Dapatkan siswa yang terkait dengan kelas-kelas tersebut
    $siswa = Siswa::whereIn('kelas_id', $kelasIds)->get();

    // Dapatkan kelompok berdasarkan siswa yang terkait
    $kelompokSiswa = KelompokBelajarSiswa::whereIn('siswa_id', $siswa->pluck('id'))->get();

    // Kelompokkan siswa berdasarkan kelompok_id
    $siswaPerKelompok = $kelompokSiswa->groupBy('kelompok_id');

    // Dapatkan data kelompok beserta nama siswa dalam kelompok tersebut
    $kelompokData = [];
    foreach ($siswaPerKelompok as $kelompokId => $siswa) {
        $kelompok = KelompokBelajar::find($kelompokId);
        $kelompokData[] = [
            'kelompok' => $kelompok,
            'siswa' => $siswa,
            
        ];
    }

    // Dapatkan entri tugas siswa terkait
    $km_tugas_siswa = KmTugasSiswa::where('kode', $tugas->kode)->get();
    //dd($km_tugas_siswa);
    // Tambahkan informasi date_send ke data siswa
    foreach ($km_tugas_siswa as $ts) {
        $siswaId = $ts->siswa_id;
        $siswaData = $siswa->firstWhere('id', $siswaId);
        //$siswaData->km_tugas_siswa = $ts;
    }
   
    return view('admin.tugas.show', [
        'title' => 'Lihat Materi',
        'plugin' => '
            <link href="' . url("/assets/cbt-malela") . '/assets/css/components/custom-list-group.css" rel="stylesheet" type="text/css" />
            <link href="' . url("/assets/cbt-malela") . '/assets/css/components/custom-media_object.css" rel="stylesheet" type="text/css" />
        ',
        'menu' => [
            'menu' => 'tugas',
            'expanded' => 'tugas',
            'collapse' => '',
            'sub' => '',
        ],
        'admin' => Admin::firstWhere('id', session('admin')->id),
        'km_tugas' => $tugas,
        'km_tugas_siswa' => $km_tugas_siswa,
        'kelompokData' => $kelompokData,
        'files' => FileModel::where('kode', $tugas->kode)->get()
    ]);
}

//     /**
//      * Show the form for editing the specified resource.
//      *
//      * @param  \App\Models\Tugas  $tugas
//      * @return \Illuminate\Http\Response
//      */
    public function edit(KmTugas $tuga)
    {
        return view('admin.tugas.edit', [
            'title' => 'Edit Tugas',
            'plugin' => '
                <link href="' . url("/assets/cbt-malela") . '/assets/css/components/custom-list-group.css" rel="stylesheet" type="text/css" />
                <link href="' . url("/assets/cbt-malela") . '/plugins/file-upload/file-upload-with-preview.min.css" rel="stylesheet" type="text/css" />
                <script src="' . url("/assets/cbt-malela") . '/plugins/file-upload/file-upload-with-preview.min.js"></script>
                <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
                <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
            ',
            'menu' => [
                'menu' => 'tugas',
                'expanded' => 'tugas',
                'collapse' => '',
                'sub' => '',
            ],
            'admin' => Admin::firstWhere('id', session('admin')->id),
            'km_tugas'  => $tuga,
            'files' => FileModel::where('kode', $tuga->kode)->get(),
            'akses_sesi' => AksesSesi::where('sesi_id', $tuga->sesi_id)->get(),
        ]);
    }

//     /**
//      * Update the specified resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  \App\Models\Tugas  $tugas
//      * @return \Illuminate\Http\Response
//      */
    public function update(Request $request, KmTugas $tuga)
    {
        $validateTugas = $request->validate([
            'nama_tugas' => 'required',
            'teks' => 'required',
        ]);
        $akses_sesi = AksesSesi::where('id', $request->sesi_id)->first();
        //dd($request->all());
        $kelompok_belajar = KelompokBelajar::where('id_kelas', $akses_sesi->kelas_id)->first();
        //dd($kelompok_belajar);
        $siswa = Siswa::where('kelas_id', $akses_sesi->kelas_id)->get();
        //dd($siswa);
        $validateTugas['guru_id'] = $kelompok_belajar->id_guru;
        $validateTugas['kelas_id'] = $akses_sesi->kelas_id;
        $validateTugas['sesi_id'] = $request->sesi_id;
        $validateTugas['due_date'] = $request->tgl . ' ' . $request->jam;

        if ($request->file('file_tugas')) {
            $files = [];
            foreach ($request->file('file_tugas') as $file) {
                array_push($files, [
                    'kode' => $tuga->kode,
                    'nama' => Str::replace('assets/files/', '', $file->store('assets/files'))
                ]);
            }
            FileModel::insert($files);
        }

        KmTugas::where('id', $tuga->id)
            ->update($validateTugas);


        return redirect('/admin/tugas')->with('pesan', "
            <script>
                swal({
                    title: 'Success!',
                    text: 'tugas sudah di edit!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }

//     /**
//      * Remove the specified resource from storage.
//      *
//      * @param  \App\Models\Tugas  $tugas
//      * @return \Illuminate\Http\Response
//      */
    public function destroy(KmTugas $tuga)
    {
        $files = FileModel::where('kode', $tuga->kode)->get();
        if ($files) {
            foreach ($files as $file) {
                Storage::delete('assets/files/' . $file->nama);
            }

            FileModel::where('kode', $tuga->kode)
                ->delete();
        }
        TugasSiswa::where('kode', $tuga->kode)
            ->delete();

        Userchat::where('key', $tuga->kode)
            ->delete();

        KmTugas::destroy($tuga->id);
        return redirect('/admin/tugas')->with('pesan', "
            <script>
                swal({
                    title: 'Success!',
                    text: 'tugas di hapus!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }

    // public function km_tugas_siswa($id)
    // {
    //     $tugas_siswa = KmTugasSiswa::firstWhere('id', $id);

    //     return view('admin.tugas.tugas-siswa', [
    //         'title' => 'Lihat Tugas',
    //         'plugin' => '
    //             <link href="' . url("/assets/cbt-malela") . '/assets/css/components/custom-list-group.css" rel="stylesheet" type="text/css" />
    //             <link href="' . url("/assets/cbt-malela") . '/assets/css/components/custom-media_object.css" rel="stylesheet" type="text/css" />
    //         ',
    //         'menu' => [
    //             'menu' => 'tugas',
    //             'expanded' => 'tugas'
    //         ],
    //         'admin' => Admin::firstWhere('id', session('admin')->id),
    //         'km_tugas_siswa'  => $tugas_siswa,
    //         'file_siswa' => FileModel::where('kode', $tugas_siswa->file)->get()
    //     ]);
    // }
//     public function nilai_tugas(Request $request, $id, $kode)
//     {

//         $data = [
//             'nilai' => $request->nilai,
//             'catatan_guru' => $request->catatan_guru
//         ];

//         TugasSiswa::where('id', $id)
//             ->update($data);

//         return redirect('/guru/tugas/' . $kode)->with('pesan', "
//             <script>
//                 swal({
//                     title: 'Success!',
//                     text: 'tugas di nilai!',
//                     type: 'success',
//                     padding: '2em'
//                 })
//             </script>
//         ");
//     }
}
