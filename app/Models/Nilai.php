<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;
    public $table = 'nilai';
    protected $guarded = ['id'];

    public function kelompok()
    {
        return $this->belongsTo(KelompokSiswa::class, 'id_kelompok', 'id_kelompok');
    }

    public function kelompokPkl()
    {
        return $this->belongsTo(KelompokPkl::class, 'id_kelompok', 'id_kelompok');
    }
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_nisn', 'nisn');
    }
    
    public function dudi()
    {
        return $this->belongsTo(DuDi::class, 'dudi_id', 'id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriNilai::class, 'kategori_id', 'id');
    }
}
