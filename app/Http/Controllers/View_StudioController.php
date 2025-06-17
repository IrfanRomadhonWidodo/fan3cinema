<?php

namespace App\Http\Controllers;

use App\Models\Studio;
use App\Models\Film;

class View_StudioController extends Controller
{
    public function index()
    {
        $studios = Studio::all();

        // Grouping film berdasarkan genre
        $films = Film::with('genre')->get()->groupBy(function ($film) {
            return $film->genre->nama_genre; // pastikan ini sesuai dengan nama kolom
        });

        return view('home', compact('studios', 'films'));
    }
}
