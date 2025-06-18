<?php
// app/Livewire/FilmManager.php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Film;
use App\Models\Genre;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Storage;

class FilmManager extends Component
{
    use WithPagination, WithFileUploads;

    // Properties untuk form
    #[Validate('required|string|max:255')]
    public $judul = '';
    
    #[Validate('required|string|max:255')]
    public $sutradara = '';
    
    #[Validate('required|integer|min:1900|max:2030')]
    public $tahun = '';
    
    #[Validate('nullable|image|max:2048')]
    public $poster = null;
    
    #[Validate('required|exists:genre,id')]
    public $genre_id = '';

    // Properties untuk state management
    public $filmId = null;
    public $isEditing = false;
    public $showModal = false;
    public $confirmingDelete = false;
    public $filmToDelete = null;
    public $currentPoster = null;
    
    // Properties untuk search dan filter dengan URL binding
    #[Url(as: 'search', history: true)]
    public $search = '';
    
    #[Url(as: 'genre_filter', history: true)]
    public $genreFilter = '';
    
    #[Url(as: 'per_page', history: true)]
    public $perPage = 10;
    
    #[Url(as: 'page', history: true)]
    public $currentPage = 1;

    // Method untuk menampilkan modal create
    public function create()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->resetForm();
        $this->showModal = true;
        $this->isEditing = false;
        
        // Dispatch browser events untuk SPA
        $this->dispatch('modal-opened', ['type' => 'create']);
        
        // Update browser history tanpa reload
        $this->js('history.pushState({}, "", window.location.pathname + window.location.search + "#create")');
    }

    // Method untuk menampilkan modal edit
    public function edit($id)
    {
        $this->resetErrorBag();
        $this->resetValidation();
        
        try {
            $film = Film::findOrFail($id);
            $this->filmId = $film->id;
            $this->judul = $film->judul;
            $this->sutradara = $film->sutradara;
            $this->tahun = $film->tahun;
            $this->genre_id = $film->genre_id;
            $this->currentPoster = $film->poster;
            $this->poster = null; // Reset file input
            $this->showModal = true;
            $this->isEditing = true;
            
            $this->dispatch('modal-opened', ['type' => 'edit', 'id' => $id]);
            
            // Update browser history tanpa reload
            $this->js('history.pushState({}, "", window.location.pathname + window.location.search + "#edit-' . $id . '")');
        } catch (\Exception $e) {
            $this->dispatch('show-alert', type: 'error', message: 'Film tidak ditemukan!');
        }
    }

    // Method untuk menyimpan data (create/update)
    public function save()
    {
        $this->validate();

        try {
            $posterPath = null;
            
            // Handle poster upload
            if ($this->poster) {
                // Delete old poster if editing
                if ($this->isEditing && $this->currentPoster) {
                    Storage::disk('public')->delete($this->currentPoster);
                }
                
                $posterPath = $this->poster->store('posters', 'public');
            } elseif ($this->isEditing) {
                // Keep current poster if no new poster uploaded
                $posterPath = $this->currentPoster;
            }

            if ($this->isEditing) {
                // Update existing film
                $film = Film::findOrFail($this->filmId);
                $film->update([
                    'judul' => $this->judul,
                    'sutradara' => $this->sutradara,
                    'tahun' => $this->tahun,
                    'poster' => $posterPath,
                    'genre_id' => $this->genre_id,
                ]);
                
                $message = 'Film berhasil diperbarui!';
                $this->dispatch('film-updated', ['id' => $this->filmId]);
            } else {
                // Create new film
                $newFilm = Film::create([
                    'judul' => $this->judul,
                    'sutradara' => $this->sutradara,
                    'tahun' => $this->tahun,
                    'poster' => $posterPath,
                    'genre_id' => $this->genre_id,
                ]);
                
                $message = 'Film berhasil ditambahkan!';
                $this->dispatch('film-created', ['id' => $newFilm->id]);
                
                // Reset ke halaman pertama untuk melihat data baru
                $this->currentPage = 1;
                $this->setPage(1);
            }

            $this->closeModal();
            $this->resetForm();
            
            // Dispatch success event
            $this->dispatch('show-alert', type: 'success', message: $message);
            
            // Kembalikan URL ke state normal
            $this->js('history.replaceState({}, "", window.location.pathname + window.location.search)');
            
        } catch (\Exception $e) {
            $this->dispatch('show-alert', type: 'error', message: 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Method untuk konfirmasi delete
    public function confirmDelete($id)
    {
        try {
            $film = Film::findOrFail($id);
            $this->filmToDelete = $id;
            $this->confirmingDelete = true;
            
            $this->dispatch('modal-opened', ['type' => 'delete', 'id' => $id]);
            
            // Update browser history tanpa reload
            $this->js('history.pushState({}, "", window.location.pathname + window.location.search + "#delete-' . $id . '")');
        } catch (\Exception $e) {
            $this->dispatch('show-alert', type: 'error', message: 'Film tidak ditemukan!');
        }
    }

    // Method untuk menghapus data
    public function delete()
    {
        try {
            if ($this->filmToDelete) {
                $film = Film::findOrFail($this->filmToDelete);
                
                // Delete poster file if exists
                if ($film->poster) {
                    Storage::disk('public')->delete($film->poster);
                }
                
                $film->delete();
                
                $this->dispatch('show-alert', type: 'success', message: 'Film berhasil dihapus!');
                $this->dispatch('film-deleted', ['id' => $this->filmToDelete]);
            }
            
            $this->confirmingDelete = false;
            $deletedId = $this->filmToDelete;
            $this->filmToDelete = null;
            
            // Cek apakah halaman saat ini masih memiliki data
            $this->checkAndAdjustPagination();
            
            // Kembalikan URL ke state normal
            $this->js('history.replaceState({}, "", window.location.pathname + window.location.search)');
            
        } catch (\Exception $e) {
            $this->dispatch('show-alert', type: 'error', message: 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Method untuk menutup modal
    public function closeModal()
    {
        $this->showModal = false;
        $this->confirmingDelete = false;
        $this->resetForm();
        $this->resetValidation();
        $this->resetErrorBag();
        
        $this->dispatch('modal-closed');
        
        // Kembalikan URL ke state normal tanpa hash
        $this->js('history.replaceState({}, "", window.location.pathname + window.location.search)');
    }

    // Method untuk reset form
    public function resetForm()
    {
        $this->filmId = null;
        $this->judul = '';
        $this->sutradara = '';
        $this->tahun = '';
        $this->poster = null;
        $this->genre_id = '';
        $this->currentPoster = null;
        $this->isEditing = false;
    }

    // Method untuk refresh data
    public function refresh()
    {
        // Pertahankan state pagination dan search
        $this->dispatch('show-alert', type: 'success', message: 'Data berhasil direfresh!');
        
        // Refresh component tanpa mengubah URL
        $this->js('$wire.$refresh()');
    }

    // Method untuk mengecek dan menyesuaikan pagination
    private function checkAndAdjustPagination()
    {
        $totalFilms = Film::when($this->search, function ($query) {
            $query->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhere('sutradara', 'like', '%' . $this->search . '%');
        })
        ->when($this->genreFilter, function ($query) {
            $query->where('genre_id', $this->genreFilter);
        })
        ->count();
        
        $maxPage = ceil($totalFilms / $this->perPage);
        
        if ($this->currentPage > $maxPage && $maxPage > 0) {
            $this->currentPage = $maxPage;
            $this->setPage($maxPage);
        } elseif ($totalFilms == 0) {
            $this->currentPage = 1;
            $this->setPage(1);
        }
    }

    // Override updatingSearch untuk reset pagination tanpa page reload
    public function updatingSearch($value)
    {
        $this->currentPage = 1;
        $this->setPage(1);
        
        // Update URL parameter tanpa reload
        $this->js('
            const url = new URL(window.location);
            if ("' . $value . '") {
                url.searchParams.set("search", "' . $value . '");
            } else {
                url.searchParams.delete("search");
            }
            url.searchParams.set("page", "1");
            history.replaceState({}, "", url);
        ');
    }

    // Override updatingGenreFilter untuk reset pagination tanpa page reload
    public function updatingGenreFilter($value)
    {
        $this->currentPage = 1;
        $this->setPage(1);
        
        // Update URL parameter tanpa reload
        $this->js('
            const url = new URL(window.location);
            if ("' . $value . '") {
                url.searchParams.set("genre_filter", "' . $value . '");
            } else {
                url.searchParams.delete("genre_filter");
            }
            url.searchParams.set("page", "1");
            history.replaceState({}, "", url);
        ');
    }

    // Override updatingPerPage untuk reset pagination tanpa page reload
    public function updatingPerPage($value)
    {
        $this->currentPage = 1;
        $this->setPage(1);
        
        // Update URL parameter tanpa reload
        $this->js('
            const url = new URL(window.location);
            url.searchParams.set("per_page", "' . $value . '");
            url.searchParams.set("page", "1");
            history.replaceState({}, "", url);
        ');
    }

    // Method untuk handle perubahan halaman
    public function updatingCurrentPage($value)
    {
        $this->setPage($value);
        
        // Update URL parameter tanpa reload
        $this->js('
            const url = new URL(window.location);
            url.searchParams.set("page", "' . $value . '");
            history.replaceState({}, "", url);
        ');
    }

    // Method untuk render view dengan optimized query
    public function render()
    {
        $films = Film::with('genre')
            ->when($this->search, function ($query) {
                $query->where('judul', 'like', '%' . $this->search . '%')
                      ->orWhere('sutradara', 'like', '%' . $this->search . '%');
            })
            ->when($this->genreFilter, function ($query) {
                $query->where('genre_id', $this->genreFilter);
            })
            ->orderBy('id', 'asc')
            ->paginate($this->perPage, ['*'], 'page', $this->currentPage);

        $genres = Genre::orderBy('nama_genre')->get();

        return view('livewire.film-manager', [
            'films' => $films,
            'genres' => $genres
        ]);
    }

    // Method untuk handle real-time updates
    public function getListeners()
    {
        return [
            'refreshComponent' => '$refresh',
            'film-created' => 'handleFilmCreated',
            'film-updated' => 'handleFilmUpdated',
            'film-deleted' => 'handleFilmDeleted',
            'popstate' => 'handleBrowserNavigation',
        ];
    }

    // Event handlers untuk real-time updates
    public function handleFilmCreated($data)
    {
        // Refresh component untuk menampilkan data baru
        $this->js('$wire.$refresh()');
    }

    public function handleFilmUpdated($data)
    {
        // Refresh component untuk menampilkan data yang diupdate
        $this->js('$wire.$refresh()');
    }

    public function handleFilmDeleted($data)
    {
        // Refresh component dan cek pagination
        $this->checkAndAdjustPagination();
        $this->js('$wire.$refresh()');
    }

    // Handle browser navigation (back/forward button)
    public function handleBrowserNavigation()
    {
        $this->closeModal();
        $this->js('$wire.$refresh()');
    }

    // Mount method untuk inisialisasi URL state
    public function mount()
    {
        // Sync dengan URL parameters
        $this->currentPage = request()->get('page', 1);
        $this->search = request()->get('search', '');
        $this->genreFilter = request()->get('genre_filter', '');
        $this->perPage = request()->get('per_page', 10);
        
        // Set initial page
        $this->setPage($this->currentPage);
        
        // Handle browser back button
        $this->js('
            window.addEventListener("popstate", function(event) {
                $wire.dispatch("popstate");
            });
        ');
    }
}