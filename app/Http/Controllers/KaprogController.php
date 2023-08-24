<?php

namespace App\Http\Controllers;

use App\Models\DuDi;
use App\Models\Guru;
use App\Models\Gurukelas;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Monitoring;
use App\Models\Kunjungan;
use App\Models\TahunAjar;
use App\Models\KelompokPkl;
use App\Models\KelompokSiswa;
use App\Models\DaftarPkl;
use App\Models\Logbook;
use App\Models\Nilai;
use App\Models\CatatanDudi;
use App\Models\Jurusan;
use App\Exports\DuDiExport;
use App\Imports\DuDiImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class KaprogController extends Controller
{
    public function index()
    {
        return view('kaprog.dashboard', [
            'title' => 'Dashboard Kepala Program',
            'plugin' => '
                <link href="' . url("/assets/cbt-malela") . '/assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
                <link href="' . url("/assets/cbt-malela") . '/assets/css/dashboard/dash_2.css" rel="stylesheet" type="text/css" />
                <link href="' . url("/assets/cbt-malela") . '/assets/css/elements/infobox.css" rel="stylesheet" type="text/css" />
                <script src="' . url("/assets/cbt-malela") . '/assets/js/dashboard/dash_1.js"></script>
            ',
            'menu' => [
                'menu' => 'dashboard',
                'expanded' => 'dashboard',
                'collapse' => '',
                'sub' => '',
            ],
            'guru' => Guru::firstWhere('nip', session('guru')->nip),
           
        ]);
    }
    public function profile()
    {
        
        return view('kaprog.profile', [
            'title' => 'My Profile',
            'plugin' => '
                <link href="' . url("assets/cbt-malela") . '/assets/css/users/user-profile.css" rel="stylesheet" type="text/css" />
            ',
            'menu' => [
                'menu' => 'profile',
                'expanded' => 'profile',
                'collapse' => '',
                'sub' => '',
            ],
            'guru' => Guru::firstWhere('nip', session('guru')->nip)
        ]);
    }
    public function edit_profile(Guru $guru, Request $request)
    {
        $rules = [
            'nama_guru' => 'required',
            'gender' => 'required',
            'no_telp' => 'required',
        ];

        $validatedData = $request->validate($rules);

        Guru::where('nip', $guru->nip)
            ->update($validatedData);

        return redirect('/kaprog/profile')->with('pesan', "
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
    public function edit_password(Request $request, Guru $guru)
    {
        if (Hash::check($request->current_password, $guru->password)) {
            $data = [
                'password' => bcrypt($request->password)
            ];
            Guru::where('nip', $guru->nip)
                ->update($data);

            return redirect('/kaprog/profile')->with('pesan', "
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

        return redirect('/kaprog/profile')->with('pesan', "
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

    // START==DUDI
    public function dudi()
    {
        $guru = Guru::firstWhere('nip', session('guru')->nip);
        $dudi = DuDi::where('jurusan_id', $guru->jurusan_id)->get();
        return view('kaprog.dudi.index', [
            'title' => 'Informasi DU/DI',
            'plugin' => '
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
                <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
                <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
            ',
            'menu' => [
                'menu' => 'master',
                'expanded' => 'master',
                'collapse' => 'master',
                'sub' => 'dudi',
            ],
            'guru' => Guru::firstWhere('nip', session('guru')->nip),
            'dudi' => $dudi,
            'jurusan' => Jurusan::all()
        ]);
    }
    public function tambah_dudi(Request $request)
    {
        $dudi = [];
        $index = 0;
        foreach ($request['nama_dudi'] as $namaDudi) {
            array_push($dudi, [
                'nama_dudi' => $namaDudi,
                'no_telp' => $request['no_telp'][$index],
                'email' => $request['email'][$index],
                'alamat' => $request['alamat'][$index],
                'password' => Hash::make('123'),
                'jurusan_id' => $request['jurusan_id'][$index],
                'role' => 5,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s', time())
            ]);
            $index++;
        }

        Dudi::insert($dudi);


            return redirect('/kaprog/dudi')->with('pesan', "
                <script>
                    swal({
                        title: 'Berhasil!',
                        text: 'data DU/DI di simpan!',
                        type: 'success',
                        padding: '2em'
                    })
                </script>
            ");
    }
    public function edit_dudi(Request $request)
    {
        $id = $request->id;
        $dudi = DuDi::firstWhere('id', $id);
        echo json_encode($dudi);
    }
    public function edit_dudi_(Request $request)
    {
        $dudi = DuDi::firstWhere('id', $request->input('id'));
        $rules = [
            'nama_dudi' => 'required',
            'no_telp' => 'required',
            'email' => 'required',
            'alamat' => 'required',
            'jurusan_id' => 'required',
            'is_active' => 'required'
        ];
        if ($request->input('email') != $dudi->email) {
            $rules['email'] = 'required|email:dns|unique:dudi';
        }

        $validate = $request->validate($rules);

        DuDi::where('id', $request->input('id'))
            ->update($validate);

        return redirect('/kaprog/dudi')->with('pesan', "
            <script>
                swal({
                    title: 'Berhasil!',
                    text: 'data DU/DI di edit!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }
    public function impor_dudi()
    {
        return response()->download('assets/file-excel/dudi.xlsx');
    }
    
    public function impor_dudi_(Request $request)
    {
       
        $dudi_excel = Excel::toArray(new DuDiImport, $request->file);
        if (count($dudi_excel) < 1) {
            return redirect('/kaprog/dudi')->with('pesan', "
                <script>
                    swal({
                        title: 'Info!',
                        text: 'tidak ada data di dalam file yang di upload',
                        type: 'info',
                        padding: '2em'
                    })
                </script>
            ");
        }

        try {
            Excel::import(new DuDiImport, $request->file);
            foreach ($dudi_excel[0] as $d) {
                $dudiData[] = [
                    'nama_dudi' => $d['nama_dudi'],
                    'no_telp' => $d['no_telp'],
                    'email' => $d['email'],
                    'alamat' => $d['alamat'],
                    'jurusan_id' => $d['jurusan_id'],
                ];
                
            }
            //dd($dudi_excel);
            
            return redirect('/kaprog/dudi')->with('pesan', "
                <script>
                    swal({
                        title: 'Berhasil!',
                        text: 'import data DU/DI berhasil!',
                        type: 'success',
                        padding: '2em'
                    })
                </script>
            ");
        
            } catch (\Exception $exceptions) {
                if ($exceptions->getCode() != 0) {
                    $pesan_error = str_replace('\'', '\`', $exceptions->errorInfo[2]);
                } else {
                    $pesan_error = $exceptions->getMessage();
                }
               
                return redirect('/kaprog/dudi')->with('pesan', "
                    <script>
                        swal({
                            title: 'Error!',
                            text: '$pesan_error',
                            type: 'error',
                            padding: '2em'
                        })
                    </script>
                ");
            }
    }

    public function hapus_dudi(DuDi $dudi)
    {
        DuDi::destroy($dudi->id);
        return redirect('/kaprog/dudi')->with('pesan', "
            <script>
                swal({
                    title: 'Berhasil!',
                    text: 'data DU/DI berhasil di hapus!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }
    public function ekspor_dudi()
    {
        return Excel::download(new DuDiExport, 'data-DUDI.xlsx');
    }

    //Peserta PKL
    public function peserta()
    {
        $sessionGuru = session('guru');

        $guru = Guru::with('jurusan')->where('nip', $sessionGuru->nip)->first();

        $daftarPkl = DaftarPkl::with(['siswa.tahun_ajar', 'siswa.kelas', 'siswa.jurusan', 'dudi'])
            ->whereHas('siswa', function ($query) use ($guru) {
                $query->where('jurusan_id', $guru->jurusan->id);
            })
            ->get();
        //dd($daftarPkl);
        return view('kaprog.peserta_pkl.indexSelect', [
            'title' => 'Informasi Peserta PKL',
            'plugin' => '
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
                <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
                <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
            ',
            'menu' => [
                'menu' => 'master',
                'expanded' => 'master',
                'collapse' => 'master',
                'sub' => 'siswa',
            ],
            'guru' => Guru::firstWhere('nip', session('guru')->nip),
            'jurusanList' => $guru->jurusan,
            'daftarPkl' => $daftarPkl
        ]);
    }
    
    //Kelompok PKL
    public function kelompok()
    { 
        $tahun_ajar = TahunAjar::orderBy('id', 'desc')->get();
        
        // $kelompok_belajar = KelompokBelajar::with('kelas')
        // ->whereHas('guru', function ($query) {
        //     $query->where('id_guru', session('guru')->id);
        // })
        // ->get();
        
        return view('kaprog.kelompok.index', [
            'title' => 'Data Kelompok PKL',
            'plugin' => '
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
                <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
                <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
            ',
            'menu' => [
                'menu' => 'kelompok',
                'expanded' => 'kelompok',
                'collapse' => '',
                'sub' => '',
               
            ],
            'guru' => Guru::firstWhere('nip', session('guru')->nip),
            'tahun_ajar' => $tahun_ajar
        ]);
    }

    //KelompokPKL
    public function showKelompok($id)
    {
        $guruList = Guru::where('jurusan_id',  session('guru')->jurusan_id)->get();
        // Mendapatkan tahun ajar berdasarkan id
        $tahunAjar = TahunAjar::findOrFail($id);
        // Mendapatkan daftar siswa berdasarkan tahun ajar dan dudi_id
        $daftarSiswa = DaftarPkl::where('tahun_ajar_id', $tahunAjar->id)->get();
        //dd($daftarSiswa->toArray());
        foreach ($daftarSiswa as $siswa) {
            // Cek apakah sudah ada kelompok untuk dudi_id dan tahun_ajar_id yang sama
            $existingKelompok = KelompokPkl::where('dudi_id', $siswa->dudi_id)
                ->where('tahun_ajar_id', $tahunAjar->id)
                ->first();
            
            //dd($existingKelompok->toArray());

            if (!$existingKelompok) {
                // Jika belum ada, buat kelompok baru
                KelompokPkl::create([
                    'dudi_id' => $siswa->dudi_id,
                    'tahun_ajar_id' => $siswa->tahun_ajar_id,
                ]);   
            }
            else{
                 //dd($kelompok->toArray());
                $idKelompok = $existingKelompok->id_kelompok;
                //dd($idKelompok);
                $existingSiswa = KelompokSiswa::where('siswa_nisn', $siswa->siswa_nisn)
                ->where('id_kelompok', $idKelompok)
                ->first();

                //dd($existingSiswa);
                if(!$existingSiswa){
                     // Loop untuk setiap siswa dan tambahkan ke tabel kelompok_siswa
                    KelompokSiswa::create([
                        'id_kelompok' => $idKelompok,
                        'siswa_nisn' => $siswa->siswa_nisn,
                    ]);
                }
  
            }
            
        }
        // Setelah semua proses selesai, tampilkan halaman yang diinginkan
        // return view('kaprog.kelompok.show', compact('tahunAjar'));
        return view('kaprog.kelompok.show', [
            'title' => 'Data Kelompok PKL',
            'plugin' => '
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
                <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
                <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
            ',
            'menu' => [
                'menu' => 'kelompok',
                'expanded' => 'kelompok',
                'collapse' => '',
                'sub' => '',
               
            ],
            'guru' => Guru::firstWhere('nip', session('guru')->nip),
            'tahun_ajar' => $tahunAjar,
            'kelompokData'=> KelompokPkl::where('tahun_ajar_id', $tahunAjar->id)->get(),
            'guruList' => $guruList
        ]);
    }

    public function edit_kelompok(Request $request)
    {
        $id_kelompok = $request->id_kelompok;
        $kelompok = KelompokPkl::firstWhere('id_kelompok', $id_kelompok);
        echo json_encode($kelompok);
    }

    public function edit_kelompok_(Request $request)
    {
        $rules = [
            'guru_nip' => 'required',
        ];
        $validate = $request->validate($rules);

        KelompokPkl::where('id_kelompok', $request->input('id_kelompok'))
            ->update($validate);

            return back()->with('pesan', "
            <script>
                swal({
                    title: 'Berhasil!',
                    text: 'Data DU/DI di edit!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }

    
   //Logbook
   public function logbook()
   {
      $guru = Guru::firstWhere('nip', session('guru')->nip);

      $logbook = Logbook::with(['kelompok.kelompok_pkl'])->whereHas('kelompok.kelompok_pkl', function ($query) use ($guru) {
        $query->where('guru_nip', $guru->nip);
        })
        ->get();

      //dd($logbook);
      
       return view('kaprog.logbook.index', [
           'title' => 'Informasi Logbook',
           'plugin' => '
               <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
               <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
               <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
               <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
           ',
           'menu' => [
               'menu' => 'kegiatan',
               'expanded' => 'daftar',
               'collapse' => 'kegiatan',
               'sub' => 'logbook',
           ],
           
           'guru' =>$guru,
           'logbook' => $logbook,
           
       ]);
   }

   //Monitoring
   public function monitoring()
   {
    $guru = Guru::firstWhere('nip', session('guru')->nip);

    $monitoring = Monitoring::whereHas('guru', function ($query) use ($guru) {
        $query->where('guru_nip', $guru->nip);
        })->get();
      //dd($monitoring);

    // Langkah 1: Dapatkan kelompok PKL yang terhubung dengan guru
    $kelompokPKL = KelompokPKL::where('guru_nip', $guru->nip)->get();

    $idKelompokList = $kelompokPKL->pluck('id_kelompok')->toArray();

    // Langkah 3: Ambil siswa berdasarkan ID kelompok yang terhubung dengan guru
    $siswaList = KelompokSiswa::whereIn('id_kelompok', $idKelompokList)->get();
    //dd($siswaList); 
    return view('kaprog.monitoring.index', [
           'title' => 'Informasi Monitoring',
           'plugin' => '
               <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
               <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
               <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
               <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
           ',
           'menu' => [
                'menu' => 'kegiatan',
                'expanded' => 'daftar',
                'collapse' => 'kegiatan',
                'sub' => 'monitoring',
           ],
           
           'monitoring' => $monitoring,
           'guru'=>$guru,
           'siswa'=>$siswaList
           
       ]);
   }

   public function tambah_monitoring(Request $request)
   {
    //dd($request);   
    $data = $request->validate([
           'tanggal' => 'required|date',
           'siswa_nisn' => 'required',
           'guru_nip' => 'required',
           'ke' => 'required',
           'catatan' => 'required', 
       ]);

       $data['created_at'] = now();

       Monitoring::insert($data);


           return redirect('/kaprog/monitoring')->with('pesan', "
               <script>
                   swal({
                       title: 'Berhasil!',
                       text: 'data Monitoring di simpan!',
                       type: 'success',
                       padding: '2em'
                   })
               </script>
           ");
   }

   public function edit_monitoring(Request $request)
   {
       $id = $request->id;
       $monitoring = Monitoring::firstWhere('id', $id);
       echo json_encode($monitoring);
   }

   public function edit_monitoring_(Request $request)
   {
       //dd($request);
       $monitoring = Monitoring::firstWhere('id', $request->input('id'));
       $rules = [
            'tanggal' => 'required|date',
            'siswa_nisn' => 'required',
            'ke' => 'required',
            'catatan' => 'required',
       ];

       $validate = $request->validate($rules);

       Monitoring::where('id', $request->input('id'))
           ->update($validate);

       return redirect('/kaprog/monitoring')->with('pesan', "
           <script>
               swal({
                   title: 'Berhasil!',
                   text: 'data Monitoring di edit!',
                   type: 'success',
                   padding: '2em'
               })
           </script>
       ");
   }
   public function hapus_monitoring($id)
    {

        Monitoring::where('id', $id)->delete();
        return redirect('/kaprog/monitoring')->with('pesan', "
            <script>
                swal({
                    title: 'Berhasil!',
                    text: 'data monitoring berhasil di hapus!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }

    //Kunjungan
   public function kunjungan()
   {
    $guru = Guru::firstWhere('nip', session('guru')->nip);

    $kunjungan = Kunjungan::whereHas('guru', function ($query) use ($guru) {
        $query->where('guru_nip', $guru->nip);
        })->get();
      //dd($monitoring);

    // Langkah 1: Dapatkan kelompok PKL yang terhubung dengan guru
    $kelompokPKL = KelompokPKL::where('guru_nip', $guru->nip)->get();
    $idKelompokList = $kelompokPKL->pluck('id_kelompok')->toArray();

    // Langkah 3: Ambil siswa berdasarkan ID kelompok yang terhubung dengan guru
    $dudiList = KelompokPKL::whereIn('id_kelompok', $idKelompokList)->get();

    return view('kaprog.kunjungan.index', [
           'title' => 'Informasi Kegiatan Kunjungan',
           'plugin' => '
               <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
               <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
               <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
               <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
           ',
           'menu' => [
                'menu' => 'kegiatan',
                'expanded' => 'daftar',
                'collapse' => 'kegiatan',
                'sub' => 'kunjungan',
           ],
           
           'kunjungan' => $kunjungan,
           'guru'=>$guru,
           'dudi'=>$dudiList
           
       ]);
   }

   public function tambah_kunjungan(Request $request)
   {
    //dd($request);   
    $data = $request->validate([
           'tanggal' => 'required|date',
           'dudi_id' => 'required',
           'guru_nip' => 'required',
           'catatan' => 'required',
           'foto' => 'image|mimes:jpeg,png,jpg|max:2048',
       ]);
       $data['status'] = 2;
       $data['created_at'] = now();
        // Proses unggahan dan penyimpanan foto
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_kunjungan');
            $data['foto'] = $fotoPath; // Simpan path foto ke dalam data
        }
        //dd($data);

       Kunjungan::insert($data);


           return redirect('/kaprog/kunjungan')->with('pesan', "
               <script>
                   swal({
                       title: 'Berhasil!',
                       text: 'data Kunjungan di simpan!',
                       type: 'success',
                       padding: '2em'
                   })
               </script>
           ");
   }

   public function edit_kunjungan(Request $request)
   {
       $id = $request->id;
       $kunjungan = Kunjungan::firstWhere('id', $id);
       echo json_encode($kunjungan);
   }

   public function edit_kunjungan_(Request $request)
   {
        $data = $request->validate([
            'id' => 'required',
            'tanggal' => 'required|date',
            'dudi_id' => 'required',
            'catatan' => 'required',
            'edit_foto' => 'image|mimes:jpeg,png,jpg|max:2048', // Validasi untuk gambar
        ]);

        // Mendapatkan data kunjungan yang akan diubah
        $kunjungan = Kunjungan::findOrFail($data['id']);

        // Menangani unggahan gambar baru
        if ($request->hasFile('edit_foto')) {
            $fotoPath = $request->file('edit_foto')->store('foto_kunjungan');
            $data['foto'] = $fotoPath; // Simpan path gambar ke dalam data
        }

        // Update data kunjungan
        $kunjungan->update($data);

       return redirect('/kaprog/kunjungan')->with('pesan', "
           <script>
               swal({
                   title: 'Berhasil!',
                   text: 'data Kunjungan di edit!',
                   type: 'success',
                   padding: '2em'
               })
           </script>
       ");
   }
   public function hapus_kunjungan($id)
    {

        Kunjungan::where('id', $id)->delete();
        return redirect('/kaprog/kunjungan')->with('pesan', "
            <script>
                swal({
                    title: 'Berhasil!',
                    text: 'data Kunjungan berhasil di hapus!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }

    //Catatan
    public function catatan()
   {
        $guru = Guru::firstWhere('nip', session('guru')->nip);
            
        // Langkah 1: Dapatkan kelompok PKL yang terhubung dengan guru
        $kelompokPKL = KelompokPKL::where('guru_nip', $guru->nip)->get();

        // Ambil semua dudi_id dari kelompok PKL yang terhubung dengan guru
        $dudiIds = $kelompokPKL->pluck('dudi_id');

        // Langkah 2: Dapatkan catatan dari CatatanDudi yang cocok dengan dudi_id
        $catatan = CatatanDudi::whereIn('dudi_id', $dudiIds)->get();


        return view('kaprog.catatan.index', [
            'title' => 'Informasi Catatan',
            'plugin' => '
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
                <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
                <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
            ',
            'menu' => [
                    'menu' => 'kegiatan',
                    'expanded' => 'daftar',
                    'collapse' => 'kegiatan',
                    'sub' => 'catatan',
            ],
            
            'catatan' => $catatan,
            'guru' =>$guru
            
        ]);
   }

   //NILAI
   public function nilai()
   {
        $guru = Guru::firstWhere('nip', session('guru')->nip);

        // Ambil data siswa, kelompok siswa, dan DUDI
        $siswaList = Siswa::where('jurusan_id', $guru->jurusan_id)->get();
        foreach ($siswaList as $siswa) {
            $kelompokSiswa = KelompokSiswa::where('siswa_nisn', $siswa->nisn)->first();
            if ($kelompokSiswa) {
                $kelompokPKL = KelompokPKL::find($kelompokSiswa->id_kelompok);
                $dudi = $kelompokPKL->dudi;
                $siswa->kelompokPKL = $kelompokPKL;
                $siswa->dudi = $dudi;
            }
        }

        //dd($siswaList); 
        return view('kaprog.nilai.index', [
            'title' => 'Informasi Catatan',
            'plugin' => '
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
                <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
                <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
            ',
            'menu' => [
                    'menu' => 'kegiatan',
                    'expanded' => 'daftar',
                    'collapse' => 'kegiatan',
                    'sub' => 'nilai',
            ],
            
            'guru'=>$guru,
            'siswa'=>$siswaList
            
        ]);
   }

   public function showNilai($nisn)
    {
            $siswa = Siswa::where('nisn', $nisn)->firstOrFail();
            // Ambil data kelompok siswa berdasarkan siswa_nisn
            $kelompokSiswa = KelompokSiswa::where('siswa_nisn', $nisn)->firstOrFail();
            
            // Ambil data DUDI berdasarkan id_kelompok dari tabel kelompok_pkl
            $dudisiswa = KelompokPKL::where('id_kelompok', $kelompokSiswa->id_kelompok)
                            ->with('dudi') 
                            ->firstOrFail()
                            ->dudi;
            $nilai= Nilai::where('siswa_nisn', $nisn)->get();
            //dd($nilai);
        
        return view('kaprog.nilai.indexSelect', [
            'title' => 'Informasi Nilai',
            'plugin' => '
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
                <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
                <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
            ',
            'menu' => [
                    'menu' => 'kegiatan',
                    'expanded' => 'daftar',
                    'collapse' => 'kegiatan',
                    'sub' => 'nilai',
            ],
            
            'guru'=>Guru::firstWhere('nip', session('guru')->nip),
            'dudisiswa'=>$dudisiswa,
            'siswa'=>$siswa,
            'nilaiList'=>$nilai
            
        ]);
    }

    
}
