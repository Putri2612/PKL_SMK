<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarPkl extends Model
{
    protected $primaryKey = 'id_daftar';

    protected $table = 'daftar_pkl';

    // protected $fillable = ['dudi_id', 'guru_nip'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_nisn', 'nisn');
    }

    public function dudi()
    {
        return $this->belongsTo(DuDi::class, 'dudi_id');
    }

     // Relasi Ke kelas
     public function kelas()
     {
         return $this->belongsTo(Kelas::class);
     }
 
     // Relasi Ke kelas
     public function jurusan()
     {
         return $this->belongsTo(Jurusan::class);
     }
 
     // Relasi Ke kelas
     public function tahun_ajar()
     {
        return $this->belongsTo(TahunAjar::class, 'tahun_ajar_id');
     }

}
