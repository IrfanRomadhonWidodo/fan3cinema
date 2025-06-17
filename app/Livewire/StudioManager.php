<?php
// app/Livewire/StudioManager.php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Studio;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Url;

class StudioManager extends Component
{
    use WithPagination;

    // Properties untuk form
    #[Validate('required|string|max:255')]
    public $nama_studio = '';
    
    #[Validate('required|integer|min:1')]
    public $kapasitas = '';

    // Properties untuk state management
    public $studioId = null;
    public $isEditing = false;
    public $showModal = false;
    public $confirmingDelete = false;
    public $studioToDelete = null;
    
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
            $studio = Studio::findOrFail($id);
            $this->studioId = $studio->id;
            $this->nama_studio = $studio->nama_studio;
            $this->kapasitas = $studio->kapasitas;
            $this->showModal = true;
            $this->isEditing = true;
            
            $this->dispatch('modal-opened', ['type' => 'edit', 'id' => $id]);
            
            // Update browser history tanpa reload
            $this->js('history.pushState({}, "", window.location.pathname + window.location.search + "#edit-' . $id . '")');
        } catch (\Exception $e) {
            $this->dispatch('show-alert', type: 'error', message: 'Studio tidak ditemukan!');
        }
    }

    // Method untuk menyimpan data (create/update)
    public function save()
    {
        $this->validate();

        try {
            if ($this->isEditing) {
                // Update existing studio
                $studio = Studio::findOrFail($this->studioId);
                $studio->update([
                    'nama_studio' => $this->nama_studio,
                    'kapasitas' => $this->kapasitas,
                ]);
                
                $message = 'Studio berhasil diperbarui!';
                $this->dispatch('studio-updated', ['id' => $this->studioId]);
            } else {
                // Create new studio
                $newStudio = Studio::create([
                    'nama_studio' => $this->nama_studio,
                    'kapasitas' => $this->kapasitas,
                ]);
                
                $message = 'Studio berhasil ditambahkan!';
                $this->dispatch('studio-created', ['id' => $newStudio->id]);
                
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
            $studio = Studio::findOrFail($id);
            $this->studioToDelete = $id;
            $this->confirmingDelete = true;
            
            $this->dispatch('modal-opened', ['type' => 'delete', 'id' => $id]);
            
            // Update browser history tanpa reload
            $this->js('history.pushState({}, "", window.location.pathname + window.location.search + "#delete-' . $id . '")');
        } catch (\Exception $e) {
            $this->dispatch('show-alert', type: 'error', message: 'Studio tidak ditemukan!');
        }
    }

    // Method untuk menghapus data
    public function delete()
    {
        try {
            if ($this->studioToDelete) {
                Studio::findOrFail($this->studioToDelete)->delete();
                
                $this->dispatch('show-alert', type: 'success', message: 'Studio berhasil dihapus!');
                $this->dispatch('studio-deleted', ['id' => $this->studioToDelete]);
            }
            
            $this->confirmingDelete = false;
            $deletedId = $this->studioToDelete;
            $this->studioToDelete = null;
            
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
        $this->studioId = null;
        $this->nama_studio = '';
        $this->kapasitas = '';
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
        $totalStudios = Studio::when($this->search, function ($query) {
            $query->where('nama_studio', 'like', '%' . $this->search . '%');
        })->count();
        
        $maxPage = ceil($totalStudios / $this->perPage);
        
        if ($this->currentPage > $maxPage && $maxPage > 0) {
            $this->currentPage = $maxPage;
            $this->setPage($maxPage);
        } elseif ($totalStudios == 0) {
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
        $studios = Studio::query()
            ->when($this->search, function ($query) {
                $query->where('nama_studio', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'asc')
            ->paginate($this->perPage, ['*'], 'page', $this->currentPage);

        return view('livewire.studio-manager', [
            'studios' => $studios
        ]);
    }

    // Method untuk handle real-time updates
    public function getListeners()
    {
        return [
            'refreshComponent' => '$refresh',
            'studio-created' => 'handleStudioCreated',
            'studio-updated' => 'handleStudioUpdated',
            'studio-deleted' => 'handleStudioDeleted',
            'popstate' => 'handleBrowserNavigation',
        ];
    }

    // Event handlers untuk real-time updates
    public function handleStudioCreated($data)
    {
        // Refresh component untuk menampilkan data baru
        $this->js('$wire.$refresh()');
    }

    public function handleStudioUpdated($data)
    {
        // Refresh component untuk menampilkan data yang diupdate
        $this->js('$wire.$refresh()');
    }

    public function handleStudioDeleted($data)
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