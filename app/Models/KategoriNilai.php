<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriNilai extends Model
{
    use HasFactory;
    public $table = 'kategori_nilai';
    protected $guarded = ['id'];
}
