<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat 1 user sebagai contoh (bisa login nanti)
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Jalankan MenuSeeder agar 20 menu ditambahkan
        $this->call([
            MenuSeeder::class,
        ]);
    }
}
