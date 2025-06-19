<?php
// app/Livewire/TiketManager.php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tiket;
use App\Models\Jadwal;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Url;

class TiketManager extends Component
{
    use WithPagination;

    // Properties untuk form
    #[Validate('required|exists:jadwal_tayang,id')]
    public $jadwal_tayang_id = '';
    
    #[Validate('required|numeric|min:0')]
    public $harga = '';

    #[Validate('required|in:tersedia,terjual')]
    public $status = 'tersedia';

    // Properties untuk state management
    public $tiketId = null;
    public $isEditing = false;
    public $showModal = false;
    public $confirmingDelete = false;
    public $tiketToDelete = null;
    
    // Properties untuk search dan filter dengan URL binding
    #[Url(as: 'search', history: true)]
    public $search = '';
    
    #[Url(as: 'status_filter', history: true)]
    public $statusFilter = '';
    
    #[Url(as: 'per_page', history: true)]
    public $perPage = 10;
    
    #[Url(as: 'page', history: true)]
    public $currentPage = 1;

    // Properties untuk data jadwal
    public $jadwalOptions = [];

    

    // Method untuk menampilkan modal create
    public function create()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->resetForm();
        $this->loadJadwalOptions();
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
            $tiket = Tiket::with(['jadwal.film', 'jadwal.studio'])->findOrFail($id);
            $this->tiketId = $tiket->id;
            $this->jadwal_tayang_id = $tiket->jadwal_tayang_id;
            $this->harga = $tiket->harga;
            $this->status = $tiket->status;
            $this->loadJadwalOptions();
            $this->showModal = true;
            $this->isEditing = true;
            
            $this->dispatch('modal-opened', ['type' => 'edit', 'id' => $id]);
            
            // Update browser history tanpa reload
            $this->js('history.pushState({}, "", window.location.pathname + window.location.search + "#edit-' . $id . '")');
        } catch (\Exception $e) {
            $this->dispatch('show-alert', type: 'error', message: 'Tiket tidak ditemukan!');
        }
    }

    // Method untuk menyimpan data (create/update)
    
    public function save()
            {
                $this->validate([
            'jadwal_tayang_id' => 'required|exists:jadwal_tayang,id',
            'harga' => 'required|numeric|min:1000',
            'status' => 'required|in:tersedia,terjual',
        ]);

        try {
            if ($this->isEditing) {
                // Update existing tiket
                $tiket = Tiket::findOrFail($this->tiketId);
                $tiket->update([
                    'jadwal_tayang_id' => $this->jadwal_tayang_id,
                    'harga' => $this->harga,
                    'status' => $this->status,
                ]);
                
                $message = 'Tiket berhasil diperbarui!';
                $this->dispatch('tiket-updated', ['id' => $this->tiketId]);
            } else {
                // Create new tiket
                $newTiket = Tiket::create([
                    'jadwal_tayang_id' => $this->jadwal_tayang_id,
                    'harga' => $this->harga,
                    'status' => $this->status,
                ]);
                
                $message = 'Tiket berhasil ditambahkan!';
                $this->dispatch('tiket-created', ['id' => $newTiket->id]);
                
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
            $tiket = Tiket::findOrFail($id);
            $this->tiketToDelete = $id;
            $this->confirmingDelete = true;
            
            $this->dispatch('modal-opened', ['type' => 'delete', 'id' => $id]);
            
            // Update browser history tanpa reload
            $this->js('history.pushState({}, "", window.location.pathname + window.location.search + "#delete-' . $id . '")');
        } catch (\Exception $e) {
            $this->dispatch('show-alert', type: 'error', message: 'Tiket tidak ditemukan!');
        }
    }

    // Method untuk menghapus data
    public function delete()
    {
        try {
            if ($this->tiketToDelete) {
                Tiket::findOrFail($this->tiketToDelete)->delete();
                
                $this->dispatch('show-alert', type: 'success', message: 'Tiket berhasil dihapus!');
                $this->dispatch('tiket-deleted', ['id' => $this->tiketToDelete]);
            }
            
            $this->confirmingDelete = false;
            $deletedId = $this->tiketToDelete;
            $this->tiketToDelete = null;
            
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
        $this->tiketId = null;
        $this->jadwal_tayang_id = '';
        $this->harga = '';
        $this->status = 'tersedia';
        $this->isEditing = false;
        $this->jadwalOptions = [];
    }

    // Method untuk load jadwal options
    public function loadJadwalOptions()
    {
        $this->jadwalOptions = Jadwal::with(['film', 'studio'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam', 'desc')
            ->get()
            ->map(function ($jadwal) {
                return [
                    'id' => $jadwal->id,
                    'label' => $jadwal->film->judul . ' - ' . $jadwal->studio->nama_studio . ' (' . 
                              $jadwal->tanggal . ' ' . $jadwal->jam . ')'
                ];
            })->toArray();
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
        $totalTiket = Tiket::when($this->search, function ($query) {
            $query->whereHas('jadwal.film', function ($q) {
                $q->where('judul', 'like', '%' . $this->search . '%');
            })->orWhereHas('jadwal.studio', function ($q) {
                $q->where('nama_studio', 'like', '%' . $this->search . '%');
            })->orWhere('harga', 'like', '%' . $this->search . '%');
        })
        ->when($this->statusFilter, function ($query) {
            $query->where('status', $this->statusFilter);
        })
        ->count();
        
        $maxPage = ceil($totalTiket / $this->perPage);
        
        if ($this->currentPage > $maxPage && $maxPage > 0) {
            $this->currentPage = $maxPage;
            $this->setPage($maxPage);
        } elseif ($totalTiket == 0) {
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

    // Override updatingStatusFilter untuk reset pagination tanpa page reload
    public function updatingStatusFilter($value)
    {
        $this->currentPage = 1;
        $this->setPage(1);
        
        // Update URL parameter tanpa reload
        $this->js('
            const url = new URL(window.location);
            if ("' . $value . '") {
                url.searchParams.set("status_filter", "' . $value . '");
            } else {
                url.searchParams.delete("status_filter");
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
        $tiket = Tiket::with(['jadwal.film', 'jadwal.studio'])
            ->when($this->search, function ($query) {
                $query->whereHas('jadwal.film', function ($q) {
                    $q->where('judul', 'like', '%' . $this->search . '%');
                })->orWhereHas('jadwal.studio', function ($q) {
                    $q->where('nama_studio', 'like', '%' . $this->search . '%');
                })->orWhere('harga', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('id', 'desc')
            ->paginate($this->perPage, ['*'], 'page', $this->currentPage);

        return view('livewire.tiket-manager', [
            'tiket' => $tiket
        ]);
    }

    // Method untuk handle real-time updates
    public function getListeners()
    {
        return [
            'refreshComponent' => '$refresh',
            'tiket-created' => 'handleTiketCreated',
            'tiket-updated' => 'handleTiketUpdated',
            'tiket-deleted' => 'handleTiketDeleted',
            'popstate' => 'handleBrowserNavigation',
        ];
    }

    // Event handlers untuk real-time updates
    public function handleTiketCreated($data)
    {
        // Refresh component untuk menampilkan data baru
        $this->js('$wire.$refresh()');
    }

    public function handleTiketUpdated($data)
    {
        // Refresh component untuk menampilkan data yang diupdate
        $this->js('$wire.$refresh()');
    }

    public function handleTiketDeleted($data)
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
        $this->statusFilter = request()->get('status_filter', '');
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