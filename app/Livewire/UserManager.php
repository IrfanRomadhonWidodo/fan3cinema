<?php
// app/Livewire/UserManager.php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UserManager extends Component
{
    use WithPagination;

    // Properties untuk form
    #[Validate('required|string|max:255')]
    public $name = '';
    
    #[Validate('required|email|max:255')]
    public $email = '';
    
    #[Validate('nullable|string|min:8')]
    public $password = '';
    
    #[Validate('nullable|same:password')]
    public $password_confirmation = '';

    // Properties untuk state management
    public $userId = null;
    public $isEditing = false;
    public $showModal = false;
    public $confirmingDelete = false;
    public $userToDelete = null;
    
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
            $user = User::findOrFail($id);
            $this->userId = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->password = '';
            $this->password_confirmation = '';
            $this->showModal = true;
            $this->isEditing = true;
            
            $this->dispatch('modal-opened', ['type' => 'edit', 'id' => $id]);
            
            // Update browser history tanpa reload
            $this->js('history.pushState({}, "", window.location.pathname + window.location.search + "#edit-' . $id . '")');
        } catch (\Exception $e) {
            $this->dispatch('show-alert', type: 'error', message: 'User tidak ditemukan!');
        }
    }

    // Method untuk menyimpan data (create/update)
    public function save()
    {
        // Dynamic validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ];

        if ($this->isEditing) {
            // Email unique validation except current user
            $rules['email'] .= '|unique:users,email,' . $this->userId;
            // Password optional for updates
            if (!empty($this->password)) {
                $rules['password'] = 'string|min:8';
                $rules['password_confirmation'] = 'same:password';
            }
        } else {
            // Email unique validation for new users
            $rules['email'] .= '|unique:users,email';
            // Password required for new users
            $rules['password'] = 'required|string|min:8';
            $rules['password_confirmation'] = 'required|same:password';
        }

        $this->validate($rules);

        try {
            if ($this->isEditing) {
                // Update existing user
                $user = User::findOrFail($this->userId);
                $updateData = [
                    'name' => $this->name,
                    'email' => $this->email,
                ];
                
                // Only update password if provided
                if (!empty($this->password)) {
                    $updateData['password'] = Hash::make($this->password);
                }
                
                $user->update($updateData);
                
                $message = 'User berhasil diperbarui!';
                $this->dispatch('user-updated', ['id' => $this->userId]);
            } else {
                // Create new user
                $newUser = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                ]);
                
                $message = 'User berhasil ditambahkan!';
                $this->dispatch('user-created', ['id' => $newUser->id]);
                
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
            $user = User::findOrFail($id);
            $this->userToDelete = $id;
            $this->confirmingDelete = true;
            
            $this->dispatch('modal-opened', ['type' => 'delete', 'id' => $id]);
            
            // Update browser history tanpa reload
            $this->js('history.pushState({}, "", window.location.pathname + window.location.search + "#delete-' . $id . '")');
        } catch (\Exception $e) {
            $this->dispatch('show-alert', type: 'error', message: 'User tidak ditemukan!');
        }
    }

    // Method untuk menghapus data
    public function delete()
    {
        try {
            if ($this->userToDelete) {
                $user = User::findOrFail($this->userToDelete);
                
                // Prevent deleting current authenticated user
            if (Auth::check() && Auth::id() == $this->userToDelete) {
                $this->dispatch('show-alert', type: 'error', message: 'Anda tidak dapat menghapus akun Anda sendiri!');
                $this->confirmingDelete = false;
                $this->userToDelete = null;
                return;
            }

                
                $user->delete();
                
                $this->dispatch('show-alert', type: 'success', message: 'User berhasil dihapus!');
                $this->dispatch('user-deleted', ['id' => $this->userToDelete]);
            }
            
            $this->confirmingDelete = false;
            $deletedId = $this->userToDelete;
            $this->userToDelete = null;
            
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
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
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
        $totalUsers = User::when($this->search, function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
        })->count();
        
        $maxPage = ceil($totalUsers / $this->perPage);
        
        if ($this->currentPage > $maxPage && $maxPage > 0) {
            $this->currentPage = $maxPage;
            $this->setPage($maxPage);
        } elseif ($totalUsers == 0) {
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
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'asc')
            ->paginate($this->perPage, ['*'], 'page', $this->currentPage);

        return view('livewire.user-manager', [
            'users' => $users
        ]);
    }

    // Method untuk handle real-time updates
    public function getListeners()
    {
        return [
            'refreshComponent' => '$refresh',
            'user-created' => 'handleUserCreated',
            'user-updated' => 'handleUserUpdated',
            'user-deleted' => 'handleUserDeleted',
            'popstate' => 'handleBrowserNavigation',
        ];
    }

    // Event handlers untuk real-time updates
    public function handleUserCreated($data)
    {
        // Refresh component untuk menampilkan data baru
        $this->js('$wire.$refresh()');
    }

    public function handleUserUpdated($data)
    {
        // Refresh component untuk menampilkan data yang diupdate
        $this->js('$wire.$refresh()');
    }

    public function handleUserDeleted($data)
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