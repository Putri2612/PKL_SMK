<?php

namespace App\Http\Controllers;

use App\Models\DuDi;
use App\Models\KelompokPkl;
use App\Models\KelompokSiswa;
use App\Models\Logbook;
use App\Models\Siswa;
use App\Models\Nilai;
use App\Models\KategoriNilai;
use App\Models\CatatanDudi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DuDiController extends Controller
{
    public function index()
    {
        return view('dudi.dashboard', [
            'title' => 'Dashboard DU/DI',
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
            'dudi' => DuDi::firstWhere('id', session('dudi')->id),
          
        ]);
    }

    public function profile()
    {
        return view('dudi.profile', [
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
            'dudi' => DuDi::firstWhere('id', session('dudi')->id),
        ]);
    }
    public function edit_profile(dudi $dudi, Request $request)
    {
        $rules = [
            'nama_dudi' => 'required',
            'no_telp' => 'required',
            'alamat' => 'required',
        ];

        $validatedData = $request->validate($rules);

      
        DuDi::where('id', $dudi->id)
            ->update($validatedData);

        return redirect('/dudi/profile')->with('pesan', "
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
    public function edit_password(Request $request, DuDi $dudi)
    {
        if (Hash::check($request->current_password, $dudi->password)) {
            $data = [
                'password' => bcrypt($request->password)
            ];
            DuDi::where('id', $dudi->id)
                ->update($data);

            return redirect('/dudi/profile')->with('pesan', "
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

        return redirect('/dudi/profile')->with('pesan', "
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

    public function Kelompok()
    {   
        $dudi = DuDi::firstWhere('id', session('dudi')->id);

        
        $kelompokData = KelompokPkl::whereHas('dudi', function ($query) use ($dudi) {
            $query->where('dudi_id', $dudi->id);
        })->get();

        
        return view('dudi.kelompok.index', [
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
            'dudi' =>$dudi,
            'kelompokData'=> $kelompokData,
        
        ]);
    }

     //Logbook
     public function logbook()
     {
        $dudi = DuDi::firstWhere('id', session('dudi')->id);
 
        $logbook = Logbook::whereHas('kelompok', function ($query) use ($dudi) {
            $query->whereHas('kelompok_pkl', function ($query) use ($dudi) {
                $query->where('dudi_id', $dudi->id);
            });
        })->get();

        //dd($logbook);
        
         return view('dudi.logbook.index', [
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
             
             'dudi' =>$dudi,
             'logbook' => $logbook,
             
         ]);
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
            'status' => 'required',
        ];

        $validate = $request->validate($rules);

        Logbook::where('id_logbook', $request->input('id_logbook'))
            ->update($validate);

        return redirect('/dudi/logbook')->with('pesan', "
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

       //Monitoring
   public function catatan()
   {
        $dudi = DuDi::firstWhere('id', session('dudi')->id);
    
        $catatan = CatatanDudi::whereHas('dudi', function ($query) use ($dudi) {
            $query->where('dudi_id', $dudi->id);
            })->get();
        //dd($monitoring);

        // Langkah 1: Dapatkan kelompok PKL yang terhubung dengan guru
        $kelompokPKL = KelompokPKL::where('dudi_id', $dudi->id)->get();

        $idKelompokList = $kelompokPKL->pluck('id_kelompok')->toArray();

        // Langkah 3: Ambil siswa berdasarkan ID kelompok yang terhubung dengan guru
        $siswaList = KelompokSiswa::whereIn('id_kelompok', $idKelompokList)->get();
        //dd($siswaList); 
        return view('dudi.catatan.index', [
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
            'dudi'=>$dudi,
            'siswa'=>$siswaList
            
        ]);
   }
   public function tambah_catatan(Request $request)
   {
    //dd($request);   
    $data = $request->validate([
           'tanggal' => 'required|date',
           'siswa_nisn' => 'required',
           'dudi_id' => 'required',
           'catatan' => 'required', 
       ]);

       $data['created_at'] = now();

       CatatanDudi::insert($data);


           return redirect('/dudi/catatan')->with('pesan', "
               <script>
                   swal({
                       title: 'Berhasil!',
                       text: 'data Catatan di simpan!',
                       type: 'success',
                       padding: '2em'
                   })
               </script>
           ");
   }

   public function edit_catatan(Request $request)
   {
       $id = $request->id;
       $catatan = CatatanDudi::firstWhere('id', $id);
       echo json_encode($catatan);
   }

   public function edit_catatan_(Request $request)
   {

       $rules = [
            'tanggal' => 'required|date',
            'siswa_nisn' => 'required',
            'catatan' => 'required',
       ];

       $validate = $request->validate($rules);

       CatatanDudi::where('id', $request->input('id'))
           ->update($validate);

       return redirect('/dudi/catatan')->with('pesan', "
           <script>
               swal({
                   title: 'Berhasil!',
                   text: 'data Catatan DU/DI di edit!',
                   type: 'success',
                   padding: '2em'
               })
           </script>
       ");
   }
   public function hapus_catatan($id)
    {

        CatatanDudi::where('id', $id)->delete();
        return redirect('/dudi/catatan')->with('pesan', "
            <script>
                swal({
                    title: 'Berhasil!',
                    text: 'data Catatan DU/DI berhasil di hapus!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }

    public function nilai()
   {
        $dudi = DuDi::firstWhere('id', session('dudi')->id);

        // Langkah 1: Dapatkan kelompok PKL yang terhubung dengan guru
        $kelompokPKL = KelompokPKL::where('dudi_id', $dudi->id)->get();

        $idKelompokList = $kelompokPKL->pluck('id_kelompok')->toArray();

        // Langkah 3: Ambil siswa berdasarkan ID kelompok yang terhubung dengan guru
        $siswaList = KelompokSiswa::whereIn('id_kelompok', $idKelompokList)->get();
        //dd($siswaList); 
        return view('dudi.nilai.index', [
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
            
            'dudi'=>$dudi,
            'siswa'=>$siswaList
            
        ]);
   }

   public function nilaiSelect($siswa_nisn)
   {
        $siswa = Siswa::where('nisn', $siswa_nisn)->firstOrFail();
         // Ambil data kelompok siswa berdasarkan siswa_nisn
         $kelompokSiswa = KelompokSiswa::where('siswa_nisn', $siswa_nisn)->firstOrFail();
        
         // Ambil data DUDI berdasarkan id_kelompok dari tabel kelompok_pkl
         $dudisiswa = KelompokPKL::where('id_kelompok', $kelompokSiswa->id_kelompok)
                           ->with('dudi') 
                           ->firstOrFail()
                           ->dudi;
        $nilaiList = Nilai::where('siswa_nisn', $siswa_nisn)->get();
      
       return view('dudi.nilai.indexSelect', [
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
           
           'dudi'=>DuDi::firstWhere('id', session('dudi')->id),
           'dudisiswa'=>$dudisiswa,
           'siswa'=>$siswa,
           'nilaiList'=>$nilaiList
           
       ]);
   }

   public function simpanNilai(Request $request)
    {
        $siswaNISN = $request->input('siswa_nisn');
        $aspek = $request->input('aspek');
        $nilaiAngka = $request->input('nilai_angka');

        $nilaiHuruf = [];
        foreach ($nilaiAngka as $nilai) {
            if ($nilai >= 90) {
                $nilaiHuruf[] = 'A';
            } elseif ($nilai >= 76) {
                $nilaiHuruf[] = 'B';
            } elseif ($nilai >= 60) {
                $nilaiHuruf[] = 'C';
            } else {
                $nilaiHuruf[] = 'D';
            }
        }

        // Loop through the input data and save each record
        for ($i = 0; $i < count($aspek); $i++) {
            Nilai::create([
                'siswa_nisn' => $siswaNISN,
                'aspek' => $aspek[$i],
                'nilai_angka' => $nilaiAngka[$i],
                'nilai_huruf' => $nilaiHuruf[$i],
            ]);
        }

        $dudi = DuDi::firstWhere('id', session('dudi')->id);

        // Langkah 1: Dapatkan kelompok PKL yang terhubung dengan guru
        $kelompokPKL = KelompokPKL::where('dudi_id', $dudi->id)->get();

        $idKelompokList = $kelompokPKL->pluck('id_kelompok')->toArray();

        // Langkah 3: Ambil siswa berdasarkan ID kelompok yang terhubung dengan guru
        $siswaList = KelompokSiswa::whereIn('id_kelompok', $idKelompokList)->get();

        return redirect('/dudi/nilai')->with('pesan', "
            <script>
                swal({
                    title: 'Berhasil!',
                    text: 'data Nilai di simpan!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");

    }
    public function showNilai($siswa_nisn)
    {
            $siswa = Siswa::where('nisn', $siswa_nisn)->firstOrFail();
            // Ambil data kelompok siswa berdasarkan siswa_nisn
            $kelompokSiswa = KelompokSiswa::where('siswa_nisn', $siswa_nisn)->firstOrFail();
            
            // Ambil data DUDI berdasarkan id_kelompok dari tabel kelompok_pkl
            $dudisiswa = KelompokPKL::where('id_kelompok', $kelompokSiswa->id_kelompok)
                            ->with('dudi') 
                            ->firstOrFail()
                            ->dudi;
            $nilai= Nilai::where('siswa_nisn', $siswa_nisn)->get();
            //dd($nilai);
        
        return view('dudi.nilai.show', [
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
            
            'dudi'=>DuDi::firstWhere('id', session('dudi')->id),
            'dudisiswa'=>$dudisiswa,
            'siswa'=>$siswa,
            'nilaiList'=>$nilai
            
        ]);
    }


}
