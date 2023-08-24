<?php

namespace App\Http\Controllers;

use App\Models\EmailSettings;
use App\Models\Guru;
use App\Models\DuDi;
use App\Models\Admin;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Jurusan;
use App\Models\KelompokPkl;
use App\Models\KelompokSiswa;
use App\Models\Logbook;
use App\Models\Nilai;
use App\Models\CatatanDudi;
use App\Models\Kunjungan;
use App\Models\Monitoring;
use App\Exports\GuruExport;
use App\Imports\GuruImport;
use App\Exports\SiswaExport;
use App\Imports\SiswaImport;
use App\Jobs\QueueEmailNotifAkun;
use App\Mail\NotifAkun;
use App\Models\DaftarPkl;
use App\Models\Notifikasi;
use App\Models\TahunAjar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{

    public function index()
    {
        return view('admin.dashboard', [
            'title' => 'Dashboard Admin',
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
            'admin' => Admin::firstWhere('id', session('admin')->id),
            'guru' => Guru::all(),
            'siswa' => Siswa::all(),
            'kelas' => Kelas::all(),
            'jurusan' => Jurusan::all(),

        ]);
    }
    public function profile()
    {
        return view('admin.profile-settings', [
            'title' => 'Profile and Settings',
            'plugin' => '
                <link href="' . url("assets/cbt-malela") . '/assets/css/users/user-profile.css" rel="stylesheet" type="text/css" />
                <link rel="stylesheet" type="text/css" href="' . url("assets/cbt-malela") . '/assets/css/forms/theme-checkbox-radio.css">
            ',
            'menu' => [
                'menu' => 'profile',
                'expanded' => 'profile',
                'collapse' => '',
                'sub' => '',
            ],
            'admin' => Admin::firstWhere('id', session('admin')->id),
            // 'email_settings' => EmailSettings::first()
        ]);
    }
    public function edit_profile(Request $request, Admin $admin)
    {
        $rules = [
            'nama_admin' => 'required|max:255',
        ];

        $validatedData = $request->validate($rules);

        if ($request->file('avatar')) {
            if ($request->gambar_lama) {
                if ($request->gambar_lama != 'default.png') {
                    Storage::delete('assets/user-profile/' . $request->gambar_lama);
                }
            }
            $validatedData['avatar'] = str_replace('assets/user-profile/', '', $request->file('avatar')->store('assets/user-profile'));
        }
        Admin::where('id', $admin->id)
            ->update($validatedData);

        return redirect('/admin/profile')->with('pesan', "
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
    public function edit_password(Request $request, Admin $admin)
    {
        if (Hash::check($request->current_password, $admin->password)) {
            $data = [
                'password' => bcrypt($request->password)
            ];
            Admin::where('id', $admin->id)
                ->update($data);

            return redirect('/admin/profile')->with('pesan', "
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

        return redirect('/admin/profile')->with('pesan', "
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
    public function smtp_email(Request $request, $id)
    {
        $data = [
            'notif_akun' => $request->notif_akun,
            'notif_materi' => $request->notif_materi,
            'notif_tugas' => $request->notif_tugas,
            'notif_ujian' => $request->notif_ujian,
        ];

        EmailSettings::where('id', $id)
            ->update($data);

        return redirect('/admin/profile')->with('pesan', "
            <script>
                swal({
                    title: 'Success!',
                    text: 'pengaturan email di ubah!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }

    // START==SIWA
    public function siswa()
    {
        return view('admin.siswa.index', [
            'title' => 'Data Siswa',
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
            'admin' => Admin::firstWhere('id', session('admin')->id),
            'siswa' => Siswa::all(),
            'kelas' => Kelas::all(),
            'jurusan' => Jurusan::all(),
            'tahun_ajar' => TahunAjar::all()
        ]);
    }
    public function tambah_siswa_(Request $request)
    {
       
        $siswa = [];
        $index = 0;
        foreach ($request['nisn'] as $NISN) {
            array_push($siswa, [
                'nisn' => $NISN,
                'nama_siswa' => $request['nama_siswa'][$index],
                'kelas_id' => $request['kelas_id'][$index],
                'jurusan_id' => $request['jurusan_id'][$index],
                'password' => bcrypt($NISN),
                'role' => 4,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s', time())
            ]);
            $index++;
        }

        Siswa::insert($siswa);

       
        return redirect('/admin/siswa')->with('pesan', "
            <script>
                swal({
                    title: 'Berhasil!',
                    text: 'data Siswa di simpan!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");

    }
    public function edit_siswa(Request $request)
    {
        $nisn = $request->nisn;
        $siswa = Siswa::firstWhere('nisn', $nisn);
        return response()->json($siswa);
    }
    public function edit_siswa_(Request $request)
    {
        $siswa = Siswa::firstWhere('nisn', $request->input('nisn'));
        $rules = [
            'nama_siswa' => 'required',
            'kelas_id' => 'required',
            'jurusan_id' => 'required',
            'is_active' => 'required'
        ];

        $validate = $request->validate($rules);

        Siswa::where('nisn', $siswa->input('nisn'))
            ->update($validate);

        return redirect('/admin/siswa')->with('pesan', "
            <script>
                swal({
                    title: 'Berhasil!',
                    text: 'data siswa di edit!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }
    public function impor_siswa()
    {
        return response()->download('assets/file-excel/siswa.xlsx');
    }
    public function impor_siswa_(Request $request)
    {

        $siswa_excel = Excel::toArray(new SiswaImport, $request->file);
        if (count($siswa_excel) < 1) {
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
            
            foreach ($siswa_excel[0] as $s) {
                $siswaData[] = [
                    'nisn' => $s['nisn'],
                    'nama_siswa' => $s['nama_siswa'],
                    'kelas_id' => $s['kelas_id'],
                    'jurusan_id' => $s['jurusan_id'],
                ];
            }
            //dd($siswaData);
            Excel::import(new SiswaImport, $request->file);

            return redirect('/admin/siswa')->with('pesan', "
                <script>
                    swal({
                        title: 'Berhasil!',
                        text: 'import data siswa berhasil!',
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

            return redirect('/admin/siswa')->with('pesan', "
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
    public function hapus_siswa($nisn)
    {

        Siswa::where('nisn', $nisn)->delete();
        return redirect('/admin/siswa')->with('pesan', "
            <script>
                swal({
                    title: 'Berhasil!',
                    text: 'data siswa berhasil di hapus!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }
    public function ekspor_siswa()
    {
        return Excel::download(new SiswaExport, 'data-siswa.xlsx');
    }

    public function guru()
    {
        return view('admin.guru.index', [
            'title' => 'Data Guru',
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
                'sub' => 'guru',
            ],
            'admin' => Admin::firstWhere('id', session('admin')->id),
            'guru' => Guru::all(),
            'jurusan' => Jurusan::all()
        ]);
    }
    public function tambah_guru_(Request $request)
    {
      
        $emails = $request->get('email');

        $guru = [];
        $email_sebelumnya = '';
        $index = 0;
        foreach ($emails as $email) {

            if ($email == $email_sebelumnya) {
                return redirect('/admin/guru')->with('pesan', "
                    <script>
                        swal({
                            title: 'Error!',
                            text: 'Duplicate data email detected!',
                            type: 'error',
                            padding: '2em'
                        })
                    </script>
                ");
            }
            array_push($guru, [

                'nip' => $request['nip'][$index],
                'nama_guru' => $request['nama_guru'][$index],
                'jurusan_id' => $request['jurusan_id'][$index],
                'email' => $email,
                'password' => bcrypt('123'),
                'role' => $request['role'][$index],
                'is_active' => 1

            ]);
            //dd($guru);
            $email_sebelumnya = $email;
            $index++;
        }

        try {
            Guru::insert($guru);

            return redirect('/admin/guru')->with('pesan', "
                <script>
                    swal({
                        title: 'Berhasil!',
                        text: 'data guru di simpan!',
                        type: 'success',
                        padding: '2em'
                    })
                </script>
            ");
        } catch (\Exception $exceptions) {
            $pesan_error = str_replace('\'', '\`', $exceptions->errorInfo[2]);
            return redirect('/admin/guru')->with('pesan', "
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
    public function edit_guru(Request $request)
    {
        $nip_guru = $request->nip;
        // /dd($nip_guru);
        $guru = guru::firstWhere('nip', $nip_guru);
        echo json_encode($guru);
    }
    public function edit_guru_(Request $request)
    {
        $guru = Guru::firstWhere('nip', $request->input('nip'));
        $rules = [
            'nama_guru' => 'required',
            'jurusan_id' => 'required',
            'role' => 'required',
            'is_active' => 'required'
        ];
        if ($request->input('email') != $guru->email) {
            $rules['email'] = 'required|email:dns|unique:guru';
        }

        $validate = $request->validate($rules);

        Guru::where('nip', $request->input('nip'))
            ->update($validate);

        return redirect('/admin/guru')->with('pesan', "
            <script>
                swal({
                    title: 'Berhasil!',
                    text: 'data guru di edit!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }
    public function impor_guru()
    {
        return response()->download('assets/file-excel/guru.xlsx');
    }
    public function impor_guru_(Request $request)
    {
        $email_settings = EmailSettings::first();

        $guru_excel = Excel::toArray(new GuruImport, $request->file);
        if (count($guru_excel) < 0) {
            return redirect('/admin/guru')->with('pesan', "
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
            Excel::import(new GuruImport, $request->file);

            if ($email_settings->notif_akun == 1) {
                foreach ($guru_excel[0] as $s) {
                    // Kirim Email ke Guru
                    $details = [
                        'nama' => $s['nama_guru'],
                        'email' => $s['email'],
                        'password' => '123'
                    ];
                    Mail::to($details['email'])->send(new NotifAkun($details));
                }
            }

            return redirect('/admin/guru')->with('pesan', "
                <script>
                    swal({
                        title: 'Berhasil!',
                        text: 'import data guru berhasil!',
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

            return redirect('/admin/guru')->with('pesan', "
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
    public function hapus_guru($nip)
    {
        Guru::where('nip', $nip)->delete();
        return redirect('/admin/guru')->with('pesan', "
            <script>
                swal({
                    title: 'Berhasil!',
                    text: 'data guru berhasil di hapus!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }
    public function ekspor_guru()
    {
        return Excel::download(new GuruExport, 'data-guru.xlsx');
    }


    //DUDI
    public function dudi()
    {
        return view('admin.dudi.index', [
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
            'admin' => Admin::firstWhere('id', session('admin')->id),
            'dudi' => DuDi::all(),
            'jurusan' => Jurusan::all()
        ]);
    }

    //Peserta PKL
    public function peserta()
    {

        return view('admin.peserta_pkl.index', [
            'title' => 'Informasi Peserta PKL',
            'plugin' => '
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
                <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
                <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
            ',
            'menu' => [
                'menu' => 'daftar',
                'expanded' => 'daftar',
                'collapse' => 'daftar',
                'sub' => 'peserta',
            ],
            'admin' => Admin::firstWhere('id', session('admin')->id),
            'jurusanList' => Jurusan::all()
        ]);
    }
    public function pesertaSelect($id)
    {
        //dd($id);
        $daftarPkl = DaftarPkl::with(['siswa.tahun_ajar', 'siswa.kelas', 'siswa.jurusan', 'dudi'])
                          ->whereHas('siswa', function ($query) use ($id) {
                              $query->where('jurusan_id', $id);
                          })
                          ->get();
        return view('admin.peserta_pkl.indexSelect', [
            'title' => 'Informasi Peserta PKL',
            'plugin' => '
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
                <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
                <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
            ',
            'menu' => [
                'menu' => 'daftar',
                'expanded' => 'daftar',
                'collapse' => 'daftar',
                'sub' => 'peserta',
            ],
            'admin' => Admin::firstWhere('id', session('admin')->id),
            'daftarPkl' => $daftarPkl
            
        ]);
    }

    public function lihatSuratBalasan($id)
    {
        $daftarPkl = DaftarPkl::findOrFail($id);
        
        $suratBalasanPath = storage_path('public/' . $daftarPkl->surat_balasan);

        return response()->file($suratBalasanPath);
    }

    //Kelompok 
    public function kelompok()
    { 
        $jurusan = Jurusan::orderBy('id', 'desc')->get();

        
        return view('admin.kelompok.index', [
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
            'admin' => Admin::firstWhere('id', session('admin')->id),
            'jurusan' => $jurusan
        ]);
    }

    public function showKelompok($id)
    {
        
        // Mendapatkan tahun ajar berdasarkan id
        $jurusan = Jurusan::findOrFail($id);
        
        $kelompokData = KelompokPkl::whereHas('dudi', function ($query) use ($jurusan) {
            $query->where('jurusan_id', $jurusan->id);
        })->get();

        $guruList = Guru::where('jurusan_id',  $jurusan->id)->get();
        
        //dd($kelompokData);
        // Setelah semua proses selesai, tampilkan halaman yang diinginkan
        // return view('kaprog.kelompok.show', compact('tahunAjar'));
        return view('admin.kelompok.show', [
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
            'admin' => Admin::firstWhere('id', session('admin')->id),
            'jurusan' => $jurusan,
            'kelompokData'=> $kelompokData,
            'guruList' => $guruList
    
        ]);
    }
    
    
    public function getSiswaByKelas($id_kelas)
    {
        $siswa = Siswa::where('kelas_id', $id_kelas)->get();
        return response()->json($siswa);
    }

    
    //Logbook
    public function logbook()
    {

        return view('admin.logbook.index', [
            'title' => 'Informasi Peserta PKL',
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
            'admin' => Admin::firstWhere('id', session('admin')->id),
            'jurusanList' => Jurusan::all()
        ]);
    }

   public function logbookSelect($id)
   {
      $admin = Admin::firstWhere('id', session('admin')->id);

      $logbook = Logbook::with(['kelompok.kelompok_pkl', 'siswa.jurusan']) // Pastikan Anda memiliki relasi yang tepat di model Logbook
        ->whereHas('siswa.jurusan', function ($query) use ($id) {
            $query->where('id', $id);
        })
        ->get();


      //dd($logbook);
      
       return view('admin.logbook.indexSelect', [
           'title' => 'Informasi Logbook',
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
           
           'admin' =>$admin,
           'logbook' => $logbook,
           
       ]);
   }

   //Monitoring
   public function monitoring()
    {

        return view('admin.monitoring.index', [
            'title' => 'Informasi Peserta PKL',
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
                'sub' => 'monitoring',
            ],
            'admin' => Admin::firstWhere('id', session('admin')->id),
            'jurusanList' => Jurusan::all()
        ]);
    }
   public function monitoringSelect($id)
   {
    $admin = Admin::firstWhere('id', session('admin')->id);
    $monitoring = Monitoring::whereHas('siswa.jurusan', function ($query) use ($id) {
        $query->where('id', $id);
    })
    ->get();

    return view('admin.monitoring.indexSelect', [
           'title' => 'Informasi Monitoring',
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
                'sub' => 'monitoring',
           ],
           
           'monitoring' => $monitoring,
           'admin'=>$admin,
       ]);
   }

   //Kunjungan
   public function kunjungan()
   {
      $admin = Admin::firstWhere('id', session('admin')->id);

      $kunjungan = Kunjungan::all();

      //dd($logbook);
      
       return view('admin.kunjungan.index', [
           'title' => 'Informasi Kunjungan',
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
               'sub' => 'kunjungan',
           ],
           
           'admin' =>$admin,
           'kunjungan' => $kunjungan,
           
       ]);
   }

   public function edit_kunjungan(Request $request)
   {
       $id = $request->id;
       $kunjungan = Kunjungan::find($id);
   
       if ($kunjungan) {
           return response()->json($kunjungan);
       } else {
           return response()->json(['error' => 'Data not found'], 404);
       }
   }

    public function edit_kunjungan_(Request $request)
    {
        //dd($request);  
        $rules = [
            'status' => 'required',
        ];

        $validate = $request->validate($rules);

        Kunjungan::where('id', $request->input('id'))
            ->update($validate);

        return redirect('/admin/kunjungan')->with('pesan', "
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

    public function catatan()
    {
            $admin = Admin::firstWhere('id', session('admin')->id);
        
            $catatan = CatatanDudi::all();

            return view('admin.catatan.index', [
                'title' => 'Informasi Catatan',
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
                        'sub' => 'catatan',
                ],
                
                'catatan' => $catatan,
                'admin'=>$admin
                
            ]);
    }

    //NILAI
    public function nilai()
    {
        $admin = Admin::firstWhere('id', session('admin')->id);

        // Ambil data siswa, kelompok siswa, dan DUDI
        $siswaList = Siswa::all();
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
         return view('admin.nilai.index', [
            'title' => 'Informasi Catatan',
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
                    'sub' => 'nilai',
            ],
            
            'admin'=>$admin,
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
    
        return view('admin.nilai.indexSelect', [
            'title' => 'Informasi Nilai',
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
                    'sub' => 'nilai',
            ],
            
            'admin'=>Admin::firstWhere('id', session('admin')->id),
            'dudisiswa'=>$dudisiswa,
            'siswa'=>$siswa,
            'nilaiList'=>$nilai
            
        ]);
    }


    //Kelas
    public function tahun_ajar()
    {
        return view('admin.tahun_ajar.index', [
            'title' => 'Data Tahun Ajar',
            'plugin' => '
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
                <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
                <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
            ',
            'menu' => [
                'menu' => 'tahun_ajar',
                'expanded' => 'tahun_ajar',
                'collapse' => '',
                'sub' => '',
            ],
            'admin' => Admin::firstWhere('id', session('admin')->id),
            'tahun_ajar' => TahunAjar::all()
            
        ]);
    }
    public function tambah_tahun_ajar(Request $request)
    {
       
        // Validasi input
        $request->validate([
            'nama' => 'required',
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date|after:periode_awal',
            'status' => 'required',
        ]);

        // Simpan data tahun ajar ke database
        $tahunAjar = new TahunAjar;
        $tahunAjar->nama = $request->nama;
        $tahunAjar->periode_awal = $request->periode_awal;
        $tahunAjar->periode_akhir = $request->periode_akhir;
        $tahunAjar->status = $request->status;
        $tahunAjar->save();
        return redirect('/admin/tahun_ajar')->with('pesan', "
            <script>
                swal({
                    title: 'Berhasil!',
                    text: 'data Tahun Ajar di simpan!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }
    public function hapus_tahun_ajar(TahunAjar $tahun_ajar)
    {
        TahunAjar::destroy($tahun_ajar->id);
        return redirect('/admin/tahun_ajar')->with('pesan', "
            <script>
                swal({
                    title: 'Berhasil!',
                    text: 'data Tahun Ajar berhasil di hapus!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
        
    }
    public function edit_tahun(Request $request)
    {
        $validate = $request->validate([
            'nama' => 'required',
            'periode_awal' => 'required',
            'periode_akhir' => 'required',
            'status' => 'required',
        ]);
        //dd($validate);

        TahunAjar::where('id', $request->input('id_tahun'))
            ->update($validate);
        //dd($validate);
        return redirect('/admin/tahun_ajar')->with('pesan', "
            <script>
                swal({
                    title: 'Berhasil!',
                    text: 'data tahun ajar di edit!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }
    
    //Kelas
    public function kelas()
    {
        return view('admin.kelas.index', [
            'title' => 'Data kelas',
            'plugin' => '
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
                <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
                <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
            ',
            'menu' => [
                'menu' => 'kelas',
                'expanded' => 'kelas',
                'collapse' => '',
                'sub' => '',
            ],
            'admin' => Admin::firstWhere('id', session('admin')->id),
            'kelas' => Kelas::with('tahun_ajar')->get(),
            'tahun_ajar' => TahunAjar::all(),
            
        ]);
    }
    public function tambah_kelas(Request $request)
    {

        $kelass = $request->get('nama_kelas');
        $tahun_ajar = $request->get('tahun_ajar_id');

        $data_kelas = [];
        $index = 0;
        foreach ($kelass as $kelas) {

            array_push($data_kelas, [

                'nama_kelas' => $kelas,
                'tahun_ajar_id' => $tahun_ajar[$index], // Assign the corresponding 'tahun_ajar_id' for each class
            ]);

            $index++;
        }

        Kelas::insert($data_kelas);

        return redirect('/admin/kelas')->with('pesan', "
            <script>
                swal({
                    title: 'Berhasil!',
                    text: 'data kelas di simpan!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }
    public function edit_kelas(Request $request)
    {
        $validate = $request->validate([
            'nama_kelas' => 'required',
            'tahun_ajar_id' => 'required'
        ]);
        Kelas::where('id', $request->input('id_kelas'))
            ->update($validate);
        
        return redirect('/admin/kelas')->with('pesan', "
            <script>
                swal({
                    title: 'Berhasil!',
                    text: 'data kelas di edit!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }
    public function hapus_kelas(Kelas $kelas)
    {
        Siswa::where('kelas_id', $kelas->id)
            ->delete();

        $materi = Materi::where('kelas_id', $kelas->id)->get();
        foreach ($materi as $m) {
            Notifikasi::where('kode', $m->kode)
                ->delete();
            Userchat::where('key', $m->kode)
                ->delete();
            FileModel::where('kode', $m->kode)
                ->delete();
        }
        Materi::where('kelas_id', $kelas->id)
            ->delete();

        $tugas = Tugas::where('kelas_id', $kelas->id)->get();
        foreach ($tugas as $t) {
            TugasSiswa::where('kode', $t->kode)
                ->delete();
            Userchat::where('key', $t->kode)
                ->delete();
            FileModel::where('kode', $t->kode)
                ->delete();
        }
        Tugas::where('kelas_id', $kelas->id)
            ->delete();

        $ujian = Ujian::where('kelas_id', $kelas->id)->get();
        foreach ($ujian as $u) {
            DetailUjian::where('kode', $u->kode)
                ->delete();
            PgSiswa::where('kode', $u->kode)
                ->delete();
            DetailEssay::where('kode', $u->kode)
                ->delete();
            EssaySiswa::where('kode', $u->kode)
                ->delete();
            WaktuUjian::where('kode', $u->kode)
                ->delete();
        }
        Ujian::where('kelas_id', $kelas->id)
            ->delete();

        Gurukelas::where('kelas_id', $kelas->id)
            ->delete();

        Kelas::destroy($kelas->id);
        return redirect('/admin/kelas')->with('pesan', "
            <script>
                swal({
                    title: 'Berhasil!',
                    text: 'data kelas berhasil di hapus!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }
    // END==KELAS

    // START==MAPEL
    public function mapel()
    {
        return view('admin.mapel.index', [
            'title' => 'Data Mapel',
            'plugin' => '
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
                <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
                <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
            ',
            'menu' => [
                'menu' => 'mapel',
                'expanded' => 'mapel',
                'collapse' => '',
                'sub' => '',
            ],
            'admin' => Admin::firstWhere('id', session('admin')->id),
            'mapel' => Mapel::all(),
            'tahun_ajar' => TahunAjar::all(),
            
            
        ]);
    }
    public function tambah_mapel(Request $request)
    {
        $mapels = $request->get('nama_mapel');
        $tahun_ajar = $request->get('tahun_ajar_id');
        $data_mapel = [];
        $index = 0;
        foreach ($mapels as $mapel) {

            array_push($data_mapel, [

                'nama_mapel' => $mapel,
                'tahun_ajar_id' => $tahun_ajar[$index],

            ]);

            $index++;
        }

        Mapel::insert($data_mapel);

        return redirect('/admin/mapel')->with('pesan', "
            <script>
                swal({
                    title: 'Berhasil!',
                    text: 'data mapel di simpan!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }
    public function edit_mapel(Request $request)
    {
        $validate = $request->validate([
            'nama_mapel' => 'required',
            'tahun_ajar_id' => 'required'
        ]);

        Mapel::where('id', $request->input('id'))
            ->update($validate);

        return redirect('/admin/mapel')->with('pesan', "
            <script>
                swal({
                    title: 'Berhasil!',
                    text: 'data mapel di edit!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }
    public function hapus_mapel(Mapel $mapel)
    {
        $materi = Materi::where('mapel_id', $mapel->id)->get();
        foreach ($materi as $m) {
            Notifikasi::where('kode', $m->kode)
                ->delete();
            Userchat::where('key', $m->kode)
                ->delete();
            FileModel::where('kode', $m->kode)
                ->delete();
        }
        Materi::where('mapel_id', $mapel->id)
            ->delete();

        $tugas = Tugas::where('mapel_id', $mapel->id)->get();
        foreach ($tugas as $t) {
            TugasSiswa::where('kode', $t->kode)
                ->delete();
            Userchat::where('key', $t->kode)
                ->delete();
            FileModel::where('kode', $t->kode)
                ->delete();
        }
        Tugas::where('mapel_id', $mapel->id)
            ->delete();

        $ujian = Ujian::where('mapel_id', $mapel->id)->get();
        foreach ($ujian as $u) {
            DetailUjian::where('kode', $u->kode)
                ->delete();
            PgSiswa::where('kode', $u->kode)
                ->delete();
            DetailEssay::where('kode', $u->kode)
                ->delete();
            EssaySiswa::where('kode', $u->kode)
                ->delete();
            WaktuUjian::where('kode', $u->kode)
                ->delete();
        }
        Ujian::where('mapel_id', $mapel->id)
            ->delete();

        Gurumapel::where('mapel_id', $mapel->id)
            ->delete();

        Mapel::destroy($mapel->id);
        return redirect('/admin/mapel')->with('pesan', "
            <script>
                swal({
                    title: 'Berhasil!',
                    text: 'data mapel berhasil di hapus!',
                    type: 'success',
                    padding: '2em'
                })
            </script>
        ");
    }
    // END==MAPEL

    public function relasi()
    {
        return view('admin.guru.relasi-index', [
            'title' => 'Data Relasi',
            'plugin' => '
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.css">
                <link rel="stylesheet" type="text/css" href="' . url("/assets/cbt-malela") . '/plugins/table/datatable/dt-global_style.css">
                <script src="' . url("/assets/cbt-malela") . '/plugins/table/datatable/datatables.js"></script>
                <script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
            ',
            'menu' => [
                'menu' => 'relasi',
                'expanded' => 'relasi',
                'collapse' => '',
                'sub' => '',
            ],
            'admin' => Admin::firstWhere('id', session('admin')->id),
            'guru' => Guru::all()
        ]);
    }
    public function relasi_guru(Guru $guru)
    {
        $tahun_ajar_aktif = TahunAjar::where('status', 1)->pluck('id')->toArray();
        //dd($tahun_ajar_aktif);
        // dd($guru);
        return view('admin.guru.relasi-guru', [
            'title' => 'Data Relasi',
            'plugin' => '

            ',
            'menu' => [
                'menu' => 'relasi',
                'expanded' => 'relasi',
                'collapse' => '',
                'sub' => '',
            ],
            'admin' => Admin::firstWhere('id', session('admin')->id),
            'guru' => $guru,
            'mapel' => Mapel::whereIn('tahun_ajar_id', $tahun_ajar_aktif)->get(),
            'kelas' => Kelas::whereIn('tahun_ajar_id', $tahun_ajar_aktif)->get(),
        ]);
    }
    public function guru_kelas(Request $request)
    {
        $id_guru = $request->id_guru;
        $id_kelas = $request->id_kelas;

        $where = [
            'guru_id' => $id_guru,
            'kelas_id' => $id_kelas,
        ];

        $result = Gurukelas::where($where)->get();

        if (count($result) > 0) {
            Gurukelas::where($where)
                ->delete();
        } else {
            Gurukelas::insert($where);
        }
    }
    public function guru_mapel(Request $request)
    {
        $id_guru = $request->id_guru;
        $id_mapel = $request->id_mapel;

        $where = [
            'guru_id' => $id_guru,
            'mapel_id' => $id_mapel,
        ];

        $result = Gurumapel::where($where)->get();

        if (count($result) > 0) {
            Gurumapel::where($where)
                ->delete();
        } else {
            Gurumapel::insert($where);
        }
    }
}
