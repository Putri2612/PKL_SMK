<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    public $table = 'siswa';
    use HasFactory;

    // protected $guarded = ['nisn'];
    protected $fillable = ['nisn', 'nama_siswa', 'kelas_id', 'jurusan_id', 'password', 'role', 'is_active'];


    protected $with = ['kelas','jurusan'];

    protected $attributes = [
        'role' => 4,
        'is_active' => 0
    ];

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

    public function dudi()
    {
        return $this->belongsTo(DUDI::class, 'dudi_id');
    }

    public function kelompokSiswa()
    {
        return $this->hasOne(KelompokSiswa::class, 'siswa_nisn', 'nisn');
    }
    
}
