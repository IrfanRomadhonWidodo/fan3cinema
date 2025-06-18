<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal_tayang';

    protected $fillable = [
        'film_id',
        'studio_id',
        'tanggal',
        'jam',
    ];

    protected $casts = [
        'tanggal' => 'date',
        // 'jam' => 'time',
    ];

    /**
     * Relasi ke model Film
     */
    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class);
    }

    /**
     * Relasi ke model Studio
     */
    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    /**
     * Scope untuk mencari jadwal berdasarkan tanggal
     */
    public function scopeByDate($query, $date)
    {
        return $query->where('tanggal', $date);
    }

    /**
     * Scope untuk mencari jadwal berdasarkan studio
     */
    public function scopeByStudio($query, $studioId)
    {
        return $query->where('studio_id', $studioId);
    }

    /**
     * Scope untuk mencari jadwal berdasarkan film
     */
    public function scopeByFilm($query, $filmId)
    {
        return $query->where('film_id', $filmId);
    }

    /**
     * Scope untuk mencari jadwal hari ini
     */
    public function scopeToday($query)
    {
        return $query->where('tanggal', now()->format('Y-m-d'));
    }

    /**
     * Accessor untuk mendapatkan waktu dalam format yang mudah dibaca
     */
    public function getWaktuTayangAttribute()
    {
        return $this->tanggal->format('d/m/Y') . ' ' . date('H:i', strtotime($this->jam));
    }
}