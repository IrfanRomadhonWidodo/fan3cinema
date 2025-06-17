<?php 
namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;

class View_FilmController extends Controller
{
    public function index()
    {
        $films = Film::with('genre')->get()->groupBy(function ($film) {
            return $film->genre->nama;
        });

        return view('layouts.film', compact('films'));
    }
}