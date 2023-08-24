<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanDudi extends Model
{
    use HasFactory;
    public $table = 'catatan_dudi';
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
}
