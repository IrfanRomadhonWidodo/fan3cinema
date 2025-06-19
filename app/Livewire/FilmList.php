<?php

namespace App\Livewire;

use App\Models\Film;
use App\Models\Genre;
use Livewire\Component;
use Carbon\Carbon;

class FilmList extends Component
{
    public $selectedDate;
    public $selectedGenre;
    public $availableGenres;

    public function mount()
    {
        $this->selectedDate = now()->toDateString();
        $this->selectedGenre = null;
        $this->availableGenres = Genre::pluck('nama_genre');
    }

    public function filterByGenre($genre)
    {
        $this->selectedGenre = $genre;
    }

    public function resetFilters()
    {
        $this->selectedGenre = null;
        $this->selectedDate = now()->toDateString();
    }

    public function render()
    {
        $query = Film::with(['genre', 'jadwal' => function ($q) {
            $q->whereDate('tanggal', $this->selectedDate);
        }]);

        if ($this->selectedGenre) {
            $query->whereHas('genre', function ($q) {
                $q->where('nama_genre', $this->selectedGenre);
            });
        }

        $films = $query->get()->filter(function ($film) {
            return $film->jadwal->isNotEmpty();
        });

        $groupedFilms = $films->groupBy(function ($film) {
            return optional($film->genre)->nama_genre ?? 'Tanpa Genre';
        });

        return view('livewire.film-list', [
            'films' => $groupedFilms,
            'selectedDate' => $this->selectedDate,
            'selectedGenre' => $this->selectedGenre,
            'availableGenres' => $this->availableGenres,
        ]);
    }
}
