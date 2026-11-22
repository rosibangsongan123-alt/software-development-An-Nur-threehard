<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Akun Owner untuk uji login (Issue #6). Password diganti setelah go-live.
        User::factory()->owner()->create([
            'name' => 'Owner An-Nur',
            'email' => 'owner@annur.test',
            'password' => 'password',
        ]);

        // Contoh Kasir di kantor pusat.
        User::factory()->kasir()->create([
            'name' => 'Kasir Pusat',
            'email' => 'kasir@annur.test',
            'password' => 'password',
        ]);
    }
}
