<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\DuDi;
use App\Models\Logbook;
use App\Models\Jurusan;
use App\Models\DaftarPKL;
use App\Models\KelompokSiswa;
use App\Models\KelompokPkl;
use App\Models\Guru;
use App\Models\Tugas;
use App\Models\Materi;
use App\Models\Notifikasi;
use App\Models\KelompokBelajar;
use App\Models\KelompokBelajarSiswa;
use App\Models\TahunAjar;
use App\Models\TugasSiswa;
use App\Models\WaktuUjian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    public function index()
    {


        return view('siswa.dashboard', [
            'title' => 'Dashboard Siswa',
            'plugin' => '
                <link href="' . url("/assets/cbt-malela") . '/assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
                <link href="' . url("/assets/cbt-malela") . '/assets/css/dashboard/dash_2.css" rel="stylesheet" type="text/css" />
                <link href="' . url("/assets/cbt-malela") . '/assets/css/elements/infobox.css" rel="stylesheet" type="text/css" />
                <script src="' . url("/assets/cbt-malela") . '/assets/js/dashboard/dash_1.js"></script>
            ',
            'menu' => [
                'menu' => 'dashboard',
                'expanded' => 'dashboard'
            ],
            'siswa' => Siswa::firstWhere('nisn', session('siswa')->nisn),
          
        ]);
    }
    public function profile()
    {

        return view('siswa.profile', [
            'title' => 'My Profile',
            'plugin' => '
                <link href="' . url("assets/cbt-malela") . '/assets/css/users/user-profile.css" rel="stylesheet" type="text/css" />
            ',
            'menu' => [
                'menu' => 'profile',
                'expanded' => 'profile'
            ],
            'siswa' => Siswa::firstWhere('nisn', session('siswa')->nisn),
            'tahun_ajar' => TahunAjar::all()
         
        ]);
    }
    public function edit_profile(Siswa $siswa, Request $request)
    {
        $rules = [
            'nama_siswa' => 'required',
            'jenis_kelamin' => 'required',
            'golongan_darah' => 'required',
            'tahun_ajar_id' => 'required',
            'no_telp' => 'required',
            'alamat' => 'required',
            'nama_ayah' => 'required',
            'nama_ibu' => 'required',
            'pekerjaan_wali' => 'required',
            'alamat_wali' => 'required'

        ];
        //dd($request->all());
        $validatedData = $request->validate($rules);

    
        
        Siswa::where('nisn', $siswa->nisn)
            ->update($validatedData);

        return redirect('/siswa/profile')->with('pesan', "
            <script>
                swal({
                    title: 'Success!',
                    text: 'profile updated!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }
    public function edit_password(Request $request, Siswa $siswa)
    {
        if (Hash::check($request->current_password, $siswa->password)) {
            $data = [
                'password' => bcrypt($request->password)
            ];
            siswa::where('nisn', $siswa->nisn)
                ->update($data);

            return redirect('/siswa/profile')->with('pesan', "
                <script>
                    swal({
                        title: 'Success!',
                        text: 'password updated!',
                        type: 'success',
                        padding: '2em'
                    })
                </script>
            ");
        }

        return redirect('/siswa/profile')->with('pesan', "
            <script>
                swal({
                    title: 'Error!',
                    text: 'current password salah!',
                    type: 'error',
                    padding: '2em'
                })
            </script>
        ");
    }

    //DUDI
    public function dudi()
    {
        $siswa = Siswa::firstWhere('nisn', session('siswa')->nisn);
        $jurusanIdSiswa = $siswa->jurusan_id;
        $dudi = DuDi::where('jurusan_id', $jurusanIdSiswa)->get();
        return view('siswa.dudi.index', [
            'title' => 'Informasi DU/DI',
            'plugin' => '
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
                <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
                <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
            ',
            'menu' => [
                'menu' => 'dudi',
                'expanded' => 'dudi',
                'collapse' => 'info',
                'sub' => 'dudi',
            ],
            
            'siswa' => Siswa::firstWhere('nisn', session('siswa')->nisn),
            'dudi' => $dudi,
            'jurusan' => Jurusan::all()
        ]);
    }

    //Daftar PKL
    public function daftar()
    {
        $nisn = session('siswa')->nisn; // Ambil NISN dari session
        
        $siswa = Siswa::where('nisn', $nisn)->first();
        //dd($siswa);
        $jurusanIdSiswa = $siswa->jurusan_id;
        $dudi = DuDi::where('jurusan_id', $jurusanIdSiswa)->get();
        $daftarPKL = DaftarPKL::where('siswa_nisn', $nisn)->first();

        $kelompokSiswa = KelompokSiswa::where('siswa_nisn', $nisn)->first();
        $guruNIP = null;

        if ($kelompokSiswa) {
           
            $kelompokPKL = KelompokPKL::firstWhere('id_kelompok',$kelompokSiswa->id_kelompok);
            //dd($kelompokPKL);
            if ($kelompokPKL) {
                $guruNIP = $kelompokPKL->guru_nip;
            }
        }
        $dataGuru = Guru::where('nip', $guruNIP)->first();

        return view('siswa.daftar.index', [
            'title' => 'Daftar PKL',
            'plugin' => '
                <link href="' . url("/assets/cbt-malela") . '/assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
                <link href="' . url("/assets/cbt-malela") . '/assets/css/dashboard/dash_2.css" rel="stylesheet" type="text/css" />
                <link href="' . url("/assets/cbt-malela") . '/assets/css/elements/infobox.css" rel="stylesheet" type="text/css" />
                <script src="' . url("/assets/cbt-malela") . '/assets/js/dashboard/dash_1.js"></script>
            ',
            'menu' => [
                'menu' => 'daftar',
                'expanded' => 'daftar',

            ],
            'siswa' => $siswa,
            'daftarPKL' => $daftarPKL,
            'dudi'=> $dudi,
            'kelompokSiswa' => $kelompokSiswa,
            'guruNIP' => $guruNIP,
            'dataGuru' => $dataGuru
          
        ]);
    }

    public function saveDaftarPkl(Request $request)
    {
        // Validasi inputan
        // dd($request);
        // dd('Before validation');
        $request->validate([
            'surat_balasan' => 'required|file|mimes:pdf|max:2048', // Sesuaikan aturan validasi file
            'siswa_nisn' => 'required|exists:siswa,nisn', // Pastikan nisn siswa ada di tabel siswa
            'dudi_id' => 'required|exists:dudi,id', // Pastikan dudi_id ada di tabel dudi
            'tahun_ajar_id' => 'required|exists:tahun_ajar,id',
        ]);

        // dd('After validation');
        //dd($request);
        // Upload file surat balasan
        $suratBalasanPath = $request->file('surat_balasan')->store('surat_balasan', 'public');

        // Simpan data daftar_pkl
        $daftarPkl = new DaftarPkl();
        $daftarPkl->siswa_nisn = $request->input('siswa_nisn');
        $daftarPkl->tahun_ajar_id = $request->input('tahun_ajar_id');
        $daftarPkl->dudi_id = $request->input('dudi_id');
        $daftarPkl->surat_balasan = $suratBalasanPath;
        $daftarPkl->timestamps = true; 
        $daftarPkl->save();

        // Redirect atau berikan respon sesuai kebutuhan
        return redirect()->back()->with('success', 'Data daftar PKL berhasil disimpan.');
    }

     //Logbook
     public function logbook()
     {
         $siswa = Siswa::firstWhere('nisn', session('siswa')->nisn);
         //$jurusanIdSiswa = $siswa->jurusan_id;

         $kelompoksiswa = KelompokSiswa::where('siswa_nisn', $siswa->nisn)->first();
         $logbook = Logbook::where('siswa_nisn', $siswa->nisn)->get();
         return view('siswa.logbook.index', [
             'title' => 'Informasi DU/DI',
             'plugin' => '
                 <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
                 <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
                 <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
                 <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
             ',
             'menu' => [
                 'menu' => 'logbook',
                 'expanded' => 'logbook',
                 'collapse' => '',
                 'sub' => '',
             ],
             
             'siswa' => Siswa::firstWhere('nisn', session('siswa')->nisn),
             'logbook' => $logbook,
             'siswa'=>$siswa,
             'kelompok'=>$kelompoksiswa
             
         ]);
     }

    public function tambah_logbook(Request $request)
    {
        $data = $request->validate([
            'tanggal' => 'required|date',
            'siswa_nisn' => 'required',
            'id_kelompok' => 'required',
            'jenis_pekerjaan' => 'required',
            'spesifikasi' => 'required',
            'masalah' => 'required',
            'penanganan' => 'required',
            'alat_bahan' => 'required',
        ]);

        // Menambahkan nilai status dan created_at
        $data['status'] = 2;
        $data['created_at'] = now();

        Logbook::insert($data);


            return redirect('/siswa/logbook')->with('pesan', "
                <script>
                    swal({
                        title: 'Berhasil!',
                        text: 'data Logbook di simpan!',
                        type: 'success',
                        padding: '2em'
                    })
                </script>
            ");
    }

    public function edit_logbook(Request $request)
    {
        $id = $request->id_logbook;
        $logbook = Logbook::firstWhere('id_logbook', $id);
        echo json_encode($logbook);
    }

    public function edit_logbook_(Request $request)
    {
        $logbook = Logbook::firstWhere('id_logbook', $request->input('id_logbook'));
        $rules = [
            'tanggal' => 'required',
            'jenis_pekerjaan' => 'required',
            'spesifikasi' => 'required',
            'masalah' => 'required',
            'penanganan' => 'required',
            'alat_bahan' => 'required',
        ];

        $validate = $request->validate($rules);

        Logbook::where('id_logbook', $request->input('id_logbook'))
            ->update($validate);

        return redirect('/siswa/logbook')->with('pesan', "
            <script>
                swal({
                    title: 'Berhasil!',
                    text: 'data Logbook di edit!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }
 
}
