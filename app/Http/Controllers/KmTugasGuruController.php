<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Admin;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\KmMateri;
use App\Models\KmTugas;
use App\Models\TahunAjar;
use App\Models\KmTugasSiswa;
use App\Models\Sesi;
use App\Models\KelompokBelajar;
use App\Models\KelompokBelajarSiswa;
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

class KmTugasGuruController extends Controller
{
    public function index()
    {
        $tahun_ajar = TahunAjar::where('status', 1)->pluck('nama')->toArray();
        return view('guru.km_tugas.indexSelect', [
            'title' => 'Data Tugas',
            'plugin' => '
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
                <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
                <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
            ',
            'menu' => [
                'menu' => 'km_tugas',
                'expanded' => 'km_tugas',
               
            ],
            //'km_materi' => KmMateri::all(),
            'guru' => Guru::firstWhere('id', session('guru')->id),
            'tahun_ajar' => $tahun_ajar,
            //'guru_mapel' => Gurumapel::where('guru_id', session('guru')->id)->get(),
            'km_tugas' => KmTugas::select('sesi_id')->where('guru_id', session('guru')->id)->groupByRaw('sesi_id')->get(),
            //'sesi' => Sesi::all()
        ]);
    }
    public function index_select($kode)
    {
        $tahun_ajar = TahunAjar::where('status', 1)->pluck('nama')->toArray();
        return view('guru.km_tugas.index', [
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
            'guru' => Guru::firstWhere('id', session('guru')->id),
            'tahun_ajar' => $tahun_ajar,
            'km_tugas' => KmTugas::where('sesi_id', $kode)->get()
        ]);
    }

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
       
        return view('guru.km_tugas.show', [
            'title' => 'Lihat Materi',
            'plugin' => '
                <link href="' . url("/assets/cbt-malela") . '/assets/css/components/custom-list-group.css" rel="stylesheet" type="text/css" />
                <link href="' . url("/assets/cbt-malela") . '/assets/css/components/custom-media_object.css" rel="stylesheet" type="text/css" />
            ',
            'menu' => [
                'menu' => 'km_tugas',
                'expanded' => 'km_tugas'
            ],
            'guru' => Guru::firstWhere('id', session('guru')->id),
            'km_tugas' => $tugas,
            'km_tugas_siswa' => $km_tugas_siswa,
            'kelompokData' => $kelompokData,
            'files' => FileModel::where('kode', $tugas->kode)->get()
        ]);
    }

    public function km_tugas_siswa($id)
    {
        $km_tugas_siswa = KmTugasSiswa::firstWhere('id', $id);

        return view('guru.km_tugas.tugas-siswa', [
            'title' => 'Lihat Tugas',
            'plugin' => '
                <link href="' . url("/assets/cbt-malela") . '/assets/css/components/custom-list-group.css" rel="stylesheet" type="text/css" />
                <link href="' . url("/assets/cbt-malela") . '/assets/css/components/custom-media_object.css" rel="stylesheet" type="text/css" />
            ',
            'menu' => [
                'menu' => 'km_tugas',
                'expanded' => 'km_tugas'
            ],
            'guru' => $km_tugas_siswa->km_tugas->guru,
            'km_tugas_siswa' => $km_tugas_siswa,
            'file_siswa' => FileModel::where('kode', $km_tugas_siswa->file)->get()
        ]);
    }

    public function nilai_tugas(Request $request, $id, $kode)
    {
        $siswa = KmTugasSiswa::findOrFail($id);
        $kelompok_id = $siswa->kelompok_id;
    
        $data = [
            'nilai' => $request->nilai,
            'catatan_guru' => $request->catatan_guru
        ];
    
        // Update nilai dan catatan_guru untuk siswa dengan id yang diberikan
        KmTugasSiswa::where('id', $id)->update($data);
    
        // Update nilai dan catatan_guru untuk siswa dengan kelompok_id yang sama
        KmTugasSiswa::where('kelompok_id', $kelompok_id)->update($data);
        
    
        return redirect('/guru/km_tugas/' . $kode)->with('pesan', "
            <script>
                swal({
                    title: 'Success!',
                    text: 'Tugas telah dinilai!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }
    
}
