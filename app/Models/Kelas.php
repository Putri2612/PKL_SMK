<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $fillable = ['nama_kelas'];


    // relasi Ke siswa
    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    // // Relasi Ke Gurukelas
    // public function gurukelas()
    // {
    //     return $this->hasMany(Gurukelas::class);
    // }
    // // Relasi Ke Materi
    // public function materi()
    // {
    //     return $this->hasMany(Materi::class);
    // }
    // // Relasi Ke Tugas
    // public function tugas()
    // {
    //     return $this->hasMany(Tugas::class);
    // }
    
    // // Relasi Ke Ujian
    // public function ujian()
    // {
    //     return $this->hasMany(Ujian::class);
    // }

    // public function akses_sesi()
    // {
    //     return $this->hasMany(AksesSesi::class);
    // }

    // // app/Models/Kelas.php

    // public function tahun_ajar()
    // {
    //     return $this->belongsTo(TahunAjar::class, 'tahun_ajar_id');
    // }



}
