<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DuDi extends Model
{
    public $table = 'dudi';
    use HasFactory;

    protected $guarded = ['id'];

    protected $attributes = [
        'role' => 5,
        'is_active' => 0
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }
}