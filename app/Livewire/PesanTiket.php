<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tiket;
use App\Models\Jadwal;
use Carbon\Carbon;

class PesanTiket extends Component
{
    public $films = [];
    public $showModal = false;
    public $selectedFilm = null;
    public $selectedJadwalId = null;
    public $jumlahTiket = 1;
    public $totalHarga = 0;
    public $kodePembayaran = '';
    public $showPayment = false;

    public function mount()
    {
        $this->loadFilms();
    }

    public function loadFilms()
    {
        $today = Carbon::today();
        
        $this->films = Jadwal::with(['film', 'studio', 'tiket'])
            ->whereDate('tanggal', $today)
            ->get()
            ->map(function ($jadwal) {
                $kapasitas = $jadwal->studio->kapasitas;
                $tiketTerjual = $jadwal->tiket->where('status', 'terjual')->count();
                $tersedia = $kapasitas - $tiketTerjual;
                
                // Hanya tampilkan jadwal yang masih memiliki tiket tersedia
                if ($tersedia > 0) {
                    $harga = optional($jadwal->tiket->first())->harga ?? 0;

                    return [
                        'id' => $jadwal->id,
                        'film' => $jadwal->film->judul,
                        'studio' => $jadwal->studio->nama_studio,
                        'jam' => $jadwal->jam,
                        'tanggal' => $jadwal->tanggal->format('d M Y'),
                        'tersedia' => $tersedia,
                        'harga' => $harga,
                        'kapasitas' => $kapasitas,
                    ];
                }
                return null;
            })
            ->filter() // Menghapus item null
            ->values(); // Reset array keys
    }

    public function pesanTiket($filmIndex)
    {
        $this->selectedFilm = $this->films[$filmIndex];
        $this->selectedJadwalId = $this->selectedFilm['id'];
        $this->jumlahTiket = 1;
        $this->totalHarga = $this->selectedFilm['harga'];
        $this->showModal = true;
        $this->showPayment = false;
    }

    public function updatedJumlahTiket()
    {
        if ($this->jumlahTiket < 1) {
            $this->jumlahTiket = 1;
        }
        
        if ($this->selectedFilm && $this->jumlahTiket > $this->selectedFilm['tersedia']) {
            $this->jumlahTiket = $this->selectedFilm['tersedia'];
        }

        if ($this->selectedFilm) {
            $this->totalHarga = $this->selectedFilm['harga'] * $this->jumlahTiket;
        }
    }

    public function incrementTiket()
    {
        if ($this->selectedFilm && $this->jumlahTiket < $this->selectedFilm['tersedia']) {
            $this->jumlahTiket++;
            $this->totalHarga = $this->selectedFilm['harga'] * $this->jumlahTiket;
        }
    }

    public function decrementTiket()
    {
        if ($this->jumlahTiket > 1) {
            $this->jumlahTiket--;
            $this->totalHarga = $this->selectedFilm['harga'] * $this->jumlahTiket;
        }
    }

    public function generateKodePembayaran()
    {
        $this->kodePembayaran = 'BNK' . strtoupper(substr(md5(time() . rand()), 0, 8));
        $this->showPayment = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedFilm = null;
        $this->selectedJadwalId = null;
        $this->jumlahTiket = 1;
        $this->totalHarga = 0;
        $this->kodePembayaran = '';
        $this->showPayment = false;
    }

    public function selesaiPemesanan()
    {
        if ($this->selectedJadwalId && $this->jumlahTiket > 0) {
            $jadwal = Jadwal::find($this->selectedJadwalId);
            
            if ($jadwal) {
                // Kurangi kapasitas studio
                $studio = $jadwal->studio;
                $newKapasitas = $studio->kapasitas - $this->jumlahTiket;
                $studio->update(['kapasitas' => $newKapasitas]);

                session()->flash('message', 'Pemesanan berhasil! Kode pembayaran: ' . $this->kodePembayaran);
                
                $this->closeModal();
                $this->loadFilms(); // Refresh data
            }
        }
    }

    public function render()
    {
        return view('livewire.pesan-tiket');
    }
}