<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokPkl extends Model
{
    protected $table = 'kelompok_pkl';
    protected $primaryKey = 'id_kelompok';

    protected $fillable = ['dudi_id', 'guru_nip', 'tahun_ajar_id'];

    public function dudi()
    {
        return $this->belongsTo(DuDi::class, 'dudi_id', 'id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_nip', 'nip');
    }

    public function tahun_ajar()
    {
        return $this->belongsTo(TahunAjar::class, 'tahun_ajar_id', 'id');
    }

    public function siswa()
    {
        return $this->hasMany(KelompokSiswa::class, 'id_kelompok', 'id_kelompok');
    }

}
