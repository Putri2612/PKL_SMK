<?php

namespace App\Http\Controllers;

use App\Models\DuDi;
use App\Models\KelompokPkl;
use App\Models\KelompokSiswa;
use App\Models\Logbook;

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
             'title' => 'Informasi DU/DI',
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
}
