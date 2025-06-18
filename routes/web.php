<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\StudioController;
use App\Http\Livewire\KelolaStudio;
use App\Livewire\StudioManager;
use App\Livewire\UserManager;
use App\Livewire\GenreManager;
use App\Livewire\FilmManager;
use App\Livewire\TiketManager;
use App\Livewire\JadwalManager;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::get('/', [App\Http\Controllers\View_StudioController::class, 'index'])->name('home');
Route::get('/film', [App\Http\Controllers\View_FilmController::class, 'index'])->name('film');



Route::get('/studios', StudioManager::class)->name('studios.index');
Route::get('/users', UserManager::class)->name('users.index');
Route::get('/genres', GenreManager::class)->name('genres.index');
Route::get('/films', FilmManager::class)->name('films.index');
Route::get('/jadwal', JadwalManager::class)->name('jadwal.index');

require __DIR__.'/auth.php';
