<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokBelajarSiswa extends Model
{
    use HasFactory;
    protected $table = 'kelompok_belajar_siswa';

    protected $fillable = [
        'kelompok_id',
        'siswa_id',
    ];

    // Relasi Ke Guru
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id');
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
    public $timestamps = false;
}
