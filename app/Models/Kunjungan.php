<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    use HasFactory;
    public $table = 'kunjungan';
    protected $guarded = ['id'];

    protected $attributes = [
        'status' => 2,
    ];


    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_nip', 'nip');
    }

    public function dudi()
    {
        return $this->belongsTo(DuDi::class, 'dudi_id', 'id');
    }
}
