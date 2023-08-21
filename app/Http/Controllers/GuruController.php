<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Gurukelas;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Sesi;
use App\Models\TahunAjar;
use App\Models\KelompokPkl;
use App\Models\KelompokSiswa;
use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
    public function index()
    {
        return view('guru.dashboard', [
            'title' => 'Dashboard Guru',
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
        return view('guru.profile', [
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

        return redirect('/guru/profile')->with('pesan', "
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

            return redirect('/guru/profile')->with('pesan', "
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

        return redirect('/guru/profile')->with('pesan', "
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
        $guru = Guru::firstWhere('nip', session('guru')->nip);
        // Mendapatkan tahun ajar berdasarkan id
        // $jurusan = Jurusan::findOrFail($id);
        
        $kelompokData = KelompokPkl::whereHas('guru', function ($query) use ($guru) {
            $query->where('guru_nip', $guru->nip);
        })->get();

        
        return view('guru.kelompok.index', [
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
            'guru' =>$guru,
            'kelompokData'=> $kelompokData,
        
        ]);
    }

    //Logbook
   public function logbook()
   {
      $guru = Guru::firstWhere('nip', session('guru')->nip);

      $logbook = Logbook::with(['kelompok.kelompok_pkl'])->get();

      //dd($logbook);
      
       return view('guru.logbook.index', [
           'title' => 'Informasi DU/DI',
           'plugin' => '
               <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
               <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
               <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
               <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
           ',
           'menu' => [
               'menu' => 'kegiatan',
               'expanded' => 'kegiatan',
               'collapse' => 'kegiatan',
               'sub' => 'logbook',
           ],
           
           'guru' =>$guru,
           'logbook' => $logbook,
           
       ]);
   }
}

