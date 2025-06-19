<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Film extends Model
{
    use HasFactory;

    protected $table = 'film';

    protected $fillable = [
        'judul', 'sutradara', 'tahun', 'poster', 'genre_id'
    ];

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    public function jadwal(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'film_id');
    }
    
}
