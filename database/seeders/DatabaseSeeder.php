<?php

namespace Database\Seeders;

use App\Models\Branch;
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
        // Cabang contoh (Issue #7).
        $cabangPusat = Branch::create([
            'name' => 'An-Nur Pusat',
            'address' => 'Jl. Merdeka No. 1, Surabaya',
            'phone' => '0315550001',
            'is_active' => true,
        ]);

        Branch::create([
            'name' => 'An-Nur Cabang Sidoarjo',
            'address' => 'Jl. Ahmad Yani No. 88, Sidoarjo',
            'phone' => '0347770002',
            'is_active' => true,
        ]);

        // Akun Owner untuk uji login (Issue #6). Password diganti setelah go-live.
        // Owner kantor pusat tidak terikat satu cabang (branch_id = null).
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
            'branch_id' => $cabangPusat->id,
        ]);
    }
}
