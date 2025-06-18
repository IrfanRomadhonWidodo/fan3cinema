<?php
// app/Livewire/JadwalManager.php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Jadwal;
use App\Models\Film;
use App\Models\Studio;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Url;

class JadwalManager extends Component
{
    use WithPagination;

    // Properties untuk form
    #[Validate('required|exists:film,id')]
    public $film_id = '';
    
    #[Validate('required|exists:studio,id')]
    public $studio_id = '';
    
    #[Validate('required|date|after_or_equal:today')]
    public $tanggal = '';
    
    #[Validate('required|string')]
    public $jam = '';

    // Properties untuk state management
    public $jadwalId = null;
    public $isEditing = false;
    public $showModal = false;
    public $confirmingDelete = false;
    public $jadwalToDelete = null;
    
    // Properties untuk search dan filter dengan URL binding
    #[Url(as: 'search', history: true)]
    public $search = '';
    
    #[Url(as: 'film_filter', history: true)]
    public $filmFilter = '';
    
    #[Url(as: 'studio_filter', history: true)]
    public $studioFilter = '';
    
    #[Url(as: 'tanggal_filter', history: true)]
    public $tanggalFilter = '';
    
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
        
        // Set default tanggal ke hari ini
        $this->tanggal = now()->format('Y-m-d');
        
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
            $jadwal = Jadwal::findOrFail($id);
            $this->jadwalId = $jadwal->id;
            $this->film_id = $jadwal->film_id;
            $this->studio_id = $jadwal->studio_id;
            $this->tanggal = $jadwal->tanggal->format('Y-m-d');
            $this->jam = $jadwal->jam;
            $this->showModal = true;
            $this->isEditing = true;
            
            $this->dispatch('modal-opened', ['type' => 'edit', 'id' => $id]);
            
            // Update browser history tanpa reload
            $this->js('history.pushState({}, "", window.location.pathname + window.location.search + "#edit-' . $id . '")');
        } catch (\Exception $e) {
            $this->dispatch('show-alert', type: 'error', message: 'Jadwal tidak ditemukan!');
        }
    }

    // Method untuk menyimpan data (create/update)
    public function save()
    {
        $this->validate();

        try {
            // Validasi tambahan untuk memastikan tidak ada jadwal yang bentrok
            $conflictQuery = Jadwal::where('studio_id', $this->studio_id)
                ->where('tanggal', $this->tanggal)
                ->where('jam', $this->jam);
            
            if ($this->isEditing) {
                $conflictQuery->where('id', '!=', $this->jadwalId);
            }
            
            if ($conflictQuery->exists()) {
                $this->addError('jam', 'Sudah ada jadwal pada studio, tanggal, dan jam yang sama.');
                return;
            }

            if ($this->isEditing) {
                // Update existing jadwal
                $jadwal = Jadwal::findOrFail($this->jadwalId);
                $jadwal->update([
                    'film_id' => $this->film_id,
                    'studio_id' => $this->studio_id,
                    'tanggal' => $this->tanggal,
                    'jam' => $this->jam,
                ]);
                
                $message = 'Jadwal berhasil diperbarui!';
                $this->dispatch('jadwal-updated', ['id' => $this->jadwalId]);
            } else {
                // Create new jadwal
                $newJadwal = Jadwal::create([
                    'film_id' => $this->film_id,
                    'studio_id' => $this->studio_id,
                    'tanggal' => $this->tanggal,
                    'jam' => $this->jam,
                ]);
                
                $message = 'Jadwal berhasil ditambahkan!';
                $this->dispatch('jadwal-created', ['id' => $newJadwal->id]);
                
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
            $jadwal = Jadwal::findOrFail($id);
            $this->jadwalToDelete = $id;
            $this->confirmingDelete = true;
            
            $this->dispatch('modal-opened', ['type' => 'delete', 'id' => $id]);
            
            // Update browser history tanpa reload
            $this->js('history.pushState({}, "", window.location.pathname + window.location.search + "#delete-' . $id . '")');
        } catch (\Exception $e) {
            $this->dispatch('show-alert', type: 'error', message: 'Jadwal tidak ditemukan!');
        }
    }

    // Method untuk menghapus data
    public function delete()
    {
        try {
            if ($this->jadwalToDelete) {
                Jadwal::findOrFail($this->jadwalToDelete)->delete();
                
                $this->dispatch('show-alert', type: 'success', message: 'Jadwal berhasil dihapus!');
                $this->dispatch('jadwal-deleted', ['id' => $this->jadwalToDelete]);
            }
            
            $this->confirmingDelete = false;
            $deletedId = $this->jadwalToDelete;
            $this->jadwalToDelete = null;
            
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
        $this->jadwalId = null;
        $this->film_id = '';
        $this->studio_id = '';
        $this->tanggal = '';
        $this->jam = '';
        $this->isEditing = false;
    }

    // Method untuk clear filters
    public function clearFilters()
    {
        $this->reset(['search', 'filmFilter', 'studioFilter', 'tanggalFilter']);
        $this->currentPage = 1;
        $this->setPage(1);
        
        // Update URL parameters
        $this->js('
            const url = new URL(window.location);
            url.searchParams.delete("search");
            url.searchParams.delete("film_filter");
            url.searchParams.delete("studio_filter");
            url.searchParams.delete("tanggal_filter");
            url.searchParams.set("page", "1");
            history.replaceState({}, "", url);
        ');
        
        $this->dispatch('show-alert', type: 'success', message: 'Filter berhasil dihapus!');
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
        $totalJadwal = Jadwal::when($this->search, function ($query) {
            $query->whereHas('film', function ($q) {
                $q->where('judul', 'like', '%' . $this->search . '%');
            })->orWhereHas('studio', function ($q) {
                $q->where('nama_studio', 'like', '%' . $this->search . '%');
            });
        })
        ->when($this->filmFilter, function ($query) {
            $query->where('film_id', $this->filmFilter);
        })
        ->when($this->studioFilter, function ($query) {
            $query->where('studio_id', $this->studioFilter);
        })
        ->when($this->tanggalFilter, function ($query) {
            $query->where('tanggal', $this->tanggalFilter);
        })
        ->count();
        
        $maxPage = ceil($totalJadwal / $this->perPage);
        
        if ($this->currentPage > $maxPage && $maxPage > 0) {
            $this->currentPage = $maxPage;
            $this->setPage($maxPage);
        } elseif ($totalJadwal == 0) {
            $this->currentPage = 1;
            $this->setPage(1);
        }
    }

    // Override updating methods untuk URL management
    public function updatingSearch($value)
    {
        $this->currentPage = 1;
        $this->setPage(1);
        
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

    public function updatingFilmFilter($value)
    {
        $this->currentPage = 1;
        $this->setPage(1);
        
        $this->js('
            const url = new URL(window.location);
            if ("' . $value . '") {
                url.searchParams.set("film_filter", "' . $value . '");
            } else {
                url.searchParams.delete("film_filter");
            }
            url.searchParams.set("page", "1");
            history.replaceState({}, "", url);
        ');
    }

    public function updatingStudioFilter($value)
    {
        $this->currentPage = 1;
        $this->setPage(1);
        
        $this->js('
            const url = new URL(window.location);
            if ("' . $value . '") {
                url.searchParams.set("studio_filter", "' . $value . '");
            } else {
                url.searchParams.delete("studio_filter");
            }
            url.searchParams.set("page", "1");
            history.replaceState({}, "", url);
        ');
    }

    public function updatingTanggalFilter($value)
    {
        $this->currentPage = 1;
        $this->setPage(1);
        
        $this->js('
            const url = new URL(window.location);
            if ("' . $value . '") {
                url.searchParams.set("tanggal_filter", "' . $value . '");
            } else {
                url.searchParams.delete("tanggal_filter");
            }
            url.searchParams.set("page", "1");
            history.replaceState({}, "", url);
        ');
    }

    public function updatingPerPage($value)
    {
        $this->currentPage = 1;
        $this->setPage(1);
        
        $this->js('
            const url = new URL(window.location);
            url.searchParams.set("per_page", "' . $value . '");
            url.searchParams.set("page", "1");
            history.replaceState({}, "", url);
        ');
    }

    public function updatingCurrentPage($value)
    {
        $this->setPage($value);
        
        $this->js('
            const url = new URL(window.location);
            url.searchParams.set("page", "' . $value . '");
            history.replaceState({}, "", url);
        ');
    }

    // Method untuk render view dengan optimized query
    public function render()
    {
        $jadwal = Jadwal::with(['film', 'studio'])
            ->when($this->search, function ($query) {
                $query->whereHas('film', function ($q) {
                    $q->where('judul', 'like', '%' . $this->search . '%');
                })->orWhereHas('studio', function ($q) {
                    $q->where('nama_studio', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filmFilter, function ($query) {
                $query->where('film_id', $this->filmFilter);
            })
            ->when($this->studioFilter, function ($query) {
                $query->where('studio_id', $this->studioFilter);
            })
            ->when($this->tanggalFilter, function ($query) {
                $query->where('tanggal', $this->tanggalFilter);
            })
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam', 'asc')
            ->paginate($this->perPage, ['*'], 'page', $this->currentPage);

        $films = Film::orderBy('judul')->get();
        $studios = Studio::orderBy('nama_studio')->get();

        return view('livewire.jadwal-manager', [
            'jadwal' => $jadwal,
            'films' => $films,
            'studios' => $studios
        ]);
    }

    // Method untuk handle real-time updates
    public function getListeners()
    {
        return [
            'refreshComponent' => '$refresh',
            'jadwal-created' => 'handleJadwalCreated',
            'jadwal-updated' => 'handleJadwalUpdated',
            'jadwal-deleted' => 'handleJadwalDeleted',
            'popstate' => 'handleBrowserNavigation',
        ];
    }

    // Event handlers untuk real-time updates
    public function handleJadwalCreated($data)
    {
        $this->js('$wire.$refresh()');
    }

    public function handleJadwalUpdated($data)
    {
        $this->js('$wire.$refresh()');
    }

    public function handleJadwalDeleted($data)
    {
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
        $this->filmFilter = request()->get('film_filter', '');
        $this->studioFilter = request()->get('studio_filter', '');
        $this->tanggalFilter = request()->get('tanggal_filter', '');
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