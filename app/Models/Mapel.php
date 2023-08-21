<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    public $table = 'mapel';
    use HasFactory;
    protected $guarded = ['id'];
    protected $fillable = ['nama_mapel', 'tahun_ajar_id'];

    // Relasi Ke Gurumapel
    public function gurumapel()
    {
        return $this->hasMany(Gurumapel::class);
    }
    // Relasi Ke Materi
    public function materi()
    {
        return $this->hasMany(Materi::class);
    }
    // Relasi Ke Tugas
    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }

    // Relasi Ke Ujian
    public function ujian()
    {
        return $this->hasMany(Ujian::class);
    }

    public function tahun_ajar()
    {
        return $this->belongsTo(TahunAjar::class, 'tahun_ajar_id');
    }
}
