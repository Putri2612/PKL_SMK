<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    public $table = 'logbook';
    use HasFactory;

    protected $guarded = ['id_logbook'];

    protected $attributes = [
        'status' => 2,
    ];

    public function kelompok()
    {
        return $this->belongsTo(KelompokSiswa::class, 'id_kelompok', 'id_kelompok');
    }

    public function kelompokSiswa()
    {
        return $this->belongsTo(KelompokPkl::class, 'id_kelompok', 'id_kelompok');
    }
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_nisn', 'nisn');
    }
}