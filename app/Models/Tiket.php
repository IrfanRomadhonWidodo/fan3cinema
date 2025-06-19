<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    use HasFactory;

    // Pastikan tabelnya sesuai
    protected $table = 'tiket';

    protected $fillable = [
        'jadwal_tayang_id',
        'harga',
        'status',
    ];

    /**
     * Relasi ke model Jadwal (tabel jadwal_tayang)
     * Set dengan foreign key yang tepat
     */
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_tayang_id');
    }

    /**
     * Akses film langsung lewat relasi jadwal
     */
    public function getFilmAttribute()
    {
        return $this->jadwal?->film;
    }

    /**
     * Akses studio langsung lewat relasi jadwal
     */
    public function getStudioAttribute()
    {
        return $this->jadwal?->studio;
    }
}
