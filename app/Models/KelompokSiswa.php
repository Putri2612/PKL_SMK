<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokSiswa extends Model
{
    use HasFactory;
    protected $table = 'kelompok_siswa';

    protected $fillable = [
        'id_kelompok',
        'siswa_nisn',
    ];

    // Relasi Ke Guru
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_nisn', 'nisn');
    }
    public function kelompok_pkl()
    {
        return $this->belongsTo(KelompokPkl::class, 'id_kelompok', 'id_kelompok');
    }
    

}
