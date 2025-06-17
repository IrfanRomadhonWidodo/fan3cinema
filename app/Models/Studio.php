<?php
// app/Models/Studio.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    use HasFactory;

    protected $table = 'studio';

    protected $fillable = [
        'nama_studio',
        'kapasitas',
    ];

    protected $casts = [
        'kapasitas' => 'integer',
    ];
}