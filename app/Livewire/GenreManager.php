<?php
// app/Livewire/GenreManager.php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Genre;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Url;

class GenreManager extends Component
{
    use WithPagination;

    // Properties untuk form
    #[Validate('required|string|max:255|unique:genre,nama_genre')]
    public $nama_genre = '';

    // Properties untuk state management
    public $genreId = null;
    public $isEditing = false;
    public $showModal = false;
    public $confirmingDelete = false;
    public $genreToDelete = null;
    
    // Properties untuk search dan filter dengan URL binding
    #[Url(as: 'search', history: true)]
    public $search = '';
    
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
            $genre = Genre::findOrFail($id);
            $this->genreId = $genre->id;
            $this->nama_genre = $genre->nama_genre;
            $this->showModal = true;
            $this->isEditing = true;
            
            $this->dispatch('modal-opened', ['type' => 'edit', 'id' => $id]);
            
            // Update browser history tanpa reload
            $this->js('history.pushState({}, "", window.location.pathname + window.location.search + "#edit-' . $id . '")');
        } catch (\Exception $e) {
            $this->dispatch('show-alert', type: 'error', message: 'Genre tidak ditemukan!');
        }
    }

    // Method untuk menyimpan data (create/update)
    public function save()
    {
        // Dynamic validation rules berdasarkan isEditing
        $rules = [
            'nama_genre' => $this->isEditing 
                ? 'required|string|max:255|unique:genre,nama_genre,' . $this->genreId
                : 'required|string|max:255|unique:genre,nama_genre'
        ];
        
        $this->validate($rules);

        try {
            if ($this->isEditing) {
                // Update existing genre
                $genre = Genre::findOrFail($this->genreId);
                $genre->update([
                    'nama_genre' => $this->nama_genre,
                ]);
                
                $message = 'Genre berhasil diperbarui!';
                $this->dispatch('genre-updated', ['id' => $this->genreId]);
            } else {
                // Create new genre
                $newGenre = Genre::create([
                    'nama_genre' => $this->nama_genre,
                ]);
                
                $message = 'Genre berhasil ditambahkan!';
                $this->dispatch('genre-created', ['id' => $newGenre->id]);
                
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
            $genre = Genre::findOrFail($id);
            
            // Check if genre has related films
            $filmCount = $genre->films()->count();
            if ($filmCount > 0) {
                $this->dispatch('show-alert', type: 'error', message: "Genre tidak dapat dihapus karena masih terdapat {$filmCount} film yang menggunakan genre ini!");
                return;
            }
            
            $this->genreToDelete = $id;
            $this->confirmingDelete = true;
            
            $this->dispatch('modal-opened', ['type' => 'delete', 'id' => $id]);
            
            // Update browser history tanpa reload
            $this->js('history.pushState({}, "", window.location.pathname + window.location.search + "#delete-' . $id . '")');
        } catch (\Exception $e) {
            $this->dispatch('show-alert', type: 'error', message: 'Genre tidak ditemukan!');
        }
    }

    // Method untuk menghapus data
    public function delete()
    {
        try {
            if ($this->genreToDelete) {
                $genre = Genre::findOrFail($this->genreToDelete);
                
                // Double check for films before deletion
                if ($genre->films()->count() > 0) {
                    $this->dispatch('show-alert', type: 'error', message: 'Genre tidak dapat dihapus karena masih terdapat film yang menggunakan genre ini!');
                    $this->confirmingDelete = false;
                    $this->genreToDelete = null;
                    return;
                }
                
                $genre->delete();
                
                $this->dispatch('show-alert', type: 'success', message: 'Genre berhasil dihapus!');
                $this->dispatch('genre-deleted', ['id' => $this->genreToDelete]);
            }
            
            $this->confirmingDelete = false;
            $deletedId = $this->genreToDelete;
            $this->genreToDelete = null;
            
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
        $this->genreId = null;
        $this->nama_genre = '';
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
        $totalGenres = Genre::when($this->search, function ($query) {
            $query->where('nama_genre', 'like', '%' . $this->search . '%');
        })->count();
        
        $maxPage = ceil($totalGenres / $this->perPage);
        
        if ($this->currentPage > $maxPage && $maxPage > 0) {
            $this->currentPage = $maxPage;
            $this->setPage($maxPage);
        } elseif ($totalGenres == 0) {
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
        $genres = Genre::query()
            ->when($this->search, function ($query) {
                $query->where('nama_genre', 'like', '%' . $this->search . '%');
            })
            ->withCount('films') // Include film count for each genre
            ->orderBy('id', 'asc')
            ->paginate($this->perPage, ['*'], 'page', $this->currentPage);

        return view('livewire.genre-manager', [
            'genres' => $genres
        ]);
    }

    // Method untuk handle real-time updates
    public function getListeners()
    {
        return [
            'refreshComponent' => '$refresh',
            'genre-created' => 'handleGenreCreated',
            'genre-updated' => 'handleGenreUpdated',
            'genre-deleted' => 'handleGenreDeleted',
            'popstate' => 'handleBrowserNavigation',
        ];
    }

    // Event handlers untuk real-time updates
    public function handleGenreCreated($data)
    {
        // Refresh component untuk menampilkan data baru
        $this->js('$wire.$refresh()');
    }

    public function handleGenreUpdated($data)
    {
        // Refresh component untuk menampilkan data yang diupdate
        $this->js('$wire.$refresh()');
    }

    public function handleGenreDeleted($data)
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