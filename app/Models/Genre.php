<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    protected $table = 'genre';

    protected $fillable = ['nama_genre'];

    // Relasi: Genre memiliki banyak film
    public function films()
    {
        return $this->hasMany(Film::class);
    }
}
