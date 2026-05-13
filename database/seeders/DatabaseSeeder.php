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
        // Create initial admin user (uncomment if needed)
        // User::factory()->create([
        //     'name' => 'Admin User',
        //     'email' => 'admin@example.com',
        // ]);

        // Uncomment the following lines if you want to seed dummy data for testing
        // $this->call([
        //     CategorySeeder::class,
        //     ProductSeeder::class,
        //     OrderSeeder::class,
        // ]);
    }
}
