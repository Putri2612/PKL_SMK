<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KmTugasSiswa extends Model
{
    public $table = 'km_tugas_siswa';
    use HasFactory;
    protected $connection = 'mysql';

    protected $guarded = ['id'];

    protected $with = ['siswa', 'km_tugas'];

    // Relasi Ke siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // public function tugas()
    // {
    //     return $this->belongsTo(KmTugas::class, 'kode', 'kode');
    // }

    public function km_tugas()
    {
        return $this->belongsTo(KmTugas::class, 'kode', 'kode');
    }

}
