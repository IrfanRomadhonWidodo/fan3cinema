<?php

// database/seeders/StudioSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Studio;

class StudioSeeder extends Seeder
{
    public function run(): void
    {
        Studio::create([
            'nama_studio' => 'Rajawali Cinema',
            'kapasitas' => 250,
        ]);

        Studio::create([
            'nama_studio' => 'CGV Cinema',
            'kapasitas' => 250,
        ]);
    }
}

