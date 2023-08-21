<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Admin;
use App\Models\Siswa;
use App\Models\KmMateri;
use App\Models\Sesi;
use App\Models\TahunAjar;
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

class MateriAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tahun_ajar_aktif = TahunAjar::where('status', 1)->pluck('id')->toArray();
        return view('admin.materi.indexSelect', [
            'title' => 'Data Materi',
            'plugin' => '
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
                <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
                <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
            ',
            'menu' => [
                'menu' => 'materi',
                'expanded' => 'materi',
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
        return view('admin.materi.index', [
            'title' => 'Data Materi',
            'plugin' => '
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
                <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
                <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
            ',
            'menu' => [
                'menu' => 'materi',
                'expanded' => 'materi',
                'collapse' => '',
                'sub' => '',
            ],
            'admin' => Admin::firstWhere('id', session('admin')->id),
            'km_materi' => KmMateri::where('sesi_id',  $kode)->get(),
            
        ]);
    }

//     /**
//      * Show the form for creating a new resource.
//      *
//      * @return \Illuminate\Http\Response
//      */
    public function create()
    {
        $tahun_ajar_aktif = TahunAjar::where('status', 1)->pluck('id')->toArray();
        return view('admin.materi.create', [
            'title' => 'Tambah Materi',
            'plugin' => '
                <link href="' . url("/assets/cbt-malela") . '/plugins/file-upload/file-upload-with-preview.min.css" rel="stylesheet" type="text/css" />
                <script src="' . url("/assets/cbt-malela") . '/plugins/file-upload/file-upload-with-preview.min.js"></script>
                <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
                <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
                <script src="' . url("/assets/cbt-malela") . '/plugins/resumable.js"></script>
            ',
            'menu' => [
                'menu' => 'materi',
                'expanded' => 'materi',
                'collapse' => '',
                'sub' => '',
            ],
            'admin' => Admin::firstWhere('id', session('admin')->id),
            'sesi' => Sesi::whereIn('tahun_ajar_id', $tahun_ajar_aktif)->get(),
            'akses_sesi' => AksesSesi::all(),
            //'guru_mapel' => Gurumapel::where('guru_id', session('guru')->id)->get(),
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
        //$email_settings = EmailSettings::first();
        $sesi = Sesi::find($request->sesi_id); // Mengambil data sesi berdasarkan id_sesi dari inputan
        $akses_sesi = AksesSesi::where('sesi_id', $sesi->id)->first(); // Mendapatkan akses_sesi berdasarkan id_ses
        $kelompok_belajar = KelompokBelajar::where('id_kelas', $akses_sesi->kelas_id)->first();
        //dd($kelompok_belajar);
        $siswa = Siswa::where('kelas_id', $akses_sesi->kelas_id)->get();

        if ($siswa->count() == 0) {
            return redirect('/admin/materi/create')->with('pesan', "
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
        $kmvalidateMateri = $request->validate([
            'nama_materi' => 'required',
            'teks' => 'required',
        ]);
        $kmvalidateMateri['kode'] = Str::random(20);
        $kmvalidateMateri['guru_id'] = $kelompok_belajar->id_guru;
        //$kmvalidateMateri['kelas_id'] = $akses_sesi->kelas_id;
        $kmvalidateMateri['sesi_id'] = $request->sesi_id;
        // $validateMateri['mapel_id'] = $request->mapel;

        if ($request->file('file_materi')) {
            $files = [];
            foreach ($request->file('file_materi') as $file) {
                array_push($files, [
                    'kode' => $kmvalidateMateri['kode'],
                    'nama' => Str::replace('assets/files/', '', $file->store('assets/files'))
                ]);
            }
            FileModel::insert($files);
        }

        KmMateri::create($kmvalidateMateri);

        return redirect('/admin/materi')->with('pesan', "
            <script>
                swal({
                    title: 'Success!',
                    text: 'materi sudah di posting!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }


//     /**
//      * Display the specified resource.
//      *
//      * @param  \App\Models\Materi  $materi
//      * @return \Illuminate\Http\Response
//      */
    public function show(KmMateri $materi)
    {
        return view('admin.materi.show', [
            'title' => 'Lihat Materi',
            'plugin' => '
                <link href="' . url("/assets/cbt-malela") . '/assets/css/components/custom-list-group.css" rel="stylesheet" type="text/css" />
                <link href="' . url("/assets/cbt-malela") . '/assets/css/components/custom-media_object.css" rel="stylesheet" type="text/css" />
            ',
            'menu' => [
                'menu' => 'materi',
                'expanded' => 'materi',
                'collapse' => '',
                'sub' => '',
            ],
            'admin' => Admin::firstWhere('id', session('admin')->id),
            'km_materi'  => $materi,
            'files' => FileModel::where('kode', $materi->kode)->get()
        ]);
    }

//     /**
//      * Show the form for editing the specified resource.
//      *
//      * @param  \App\Models\Materi  $materi
//      * @return \Illuminate\Http\Response
//      */
    public function edit(KmMateri $materi)
    {
        return view('admin.materi.edit', [
            'title' => 'Tambah Materi',
            'plugin' => '
                <link href="' . url("/assets/cbt-malela") . '/assets/css/components/custom-list-group.css" rel="stylesheet" type="text/css" />
                <link href="' . url("/assets/cbt-malela") . '/plugins/file-upload/file-upload-with-preview.min.css" rel="stylesheet" type="text/css" />
                <script src="' . url("/assets/cbt-malela") . '/plugins/file-upload/file-upload-with-preview.min.js"></script>
                <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
                <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
            ',
            'menu' => [
                'menu' => 'materi',
                'expanded' => 'materi',
                'collapse' => '',
                'sub' => '',
            ],
            'admin' => Admin::firstWhere('id', session('admin')->id),
            'km_materi'  => $materi,
            'files' => FileModel::where('kode', $materi->kode)->get(),
            'akses_sesi' => AksesSesi::where('sesi_id', $materi->sesi_id)->get(),
           
        ]);
    }

//     /**
//      * Update the specified resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  \App\Models\Materi  $materi
//      * @return \Illuminate\Http\Response
//      */
    public function update(Request $request, KmMateri $materi)
    {
        $validateMateri = $request->validate([
            'nama_materi' => 'required',
            'teks' => 'required',
        ]);
        $akses_sesi = AksesSesi::where('id', $request->sesi_id)->first();
        //dd($request->all());
        $kelompok_belajar = KelompokBelajar::where('id_kelas', $akses_sesi->kelas_id)->first();
        //dd($kelompok_belajar);
        $siswa = Siswa::where('kelas_id', $akses_sesi->kelas_id)->get();
        //dd($siswa);
        $validateMateri['guru_id'] = $kelompok_belajar->id_guru;
        $validateMateri['kelas_id'] = $akses_sesi->kelas_id;
        $validateMateri['sesi_id'] = $request->sesi_id;

        if ($request->file('file_materi')) {
            $files = [];
            foreach ($request->file('file_materi') as $file) {
                array_push($files, [
                    'kode' => $materi->kode,
                    'nama' => Str::replace('assets/files/', '', $file->store('assets/files'))
                ]);
            }
            FileModel::insert($files);
        }

        KmMateri::where('id', $materi->id)
            ->update($validateMateri);

        return redirect('/admin/materi')->with('pesan', "
            <script>
                swal({
                    title: 'Success!',
                    text: 'materi sudah di update!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }

//     /**
//      * Remove the specified resource from storage.
//      *
//      * @param  \App\Models\Materi  $materi
//      * @return \Illuminate\Http\Response
//      */
    public function destroy(KmMateri $materi)
    {
        $files = FileModel::where('kode', $materi->kode)->get();
        if ($files) {
            foreach ($files as $file) {
                Storage::delete('assets/files/' . $file->nama);
            }

            FileModel::where('kode', $materi->kode)
                ->delete();
        }

        Userchat::where('key', $materi->kode)
            ->delete();

        KmMateri::destroy($materi->id);
        return redirect('/admin/materi')->with('pesan', "
            <script>
                swal({
                    title: 'Success!',
                    text: 'materi di hapus!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }
    }
