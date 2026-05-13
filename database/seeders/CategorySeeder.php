<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Electronic devices and gadgets', 'icon' => '📱'],
            ['name' => 'Clothing', 'slug' => 'clothing', 'description' => 'Fashion and apparel', 'icon' => '👕'],
            ['name' => 'Home & Garden', 'slug' => 'home-garden', 'description' => 'Home improvement and garden supplies', 'icon' => '🏠'],
            ['name' => 'Books', 'slug' => 'books', 'description' => 'Books and educational materials', 'icon' => '📚'],
            ['name' => 'Sports', 'slug' => 'sports', 'description' => 'Sports equipment and accessories', 'icon' => '⚽'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
