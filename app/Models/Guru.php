<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    public $table = 'guru';

    use HasFactory;

    protected $attributes = [
        'is_active' => 0
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
    

}
