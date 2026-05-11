<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics',  'icon' => '📱', 'description' => 'Phones, laptops, gadgets'],
            ['name' => 'Clothing',     'icon' => '👗', 'description' => 'Men and women fashion'],
            ['name' => 'Food',         'icon' => '🍎', 'description' => 'Fresh and packaged food'],
            ['name' => 'Home & Living','icon' => '🏠', 'description' => 'Furniture and appliances'],
            ['name' => 'Sports',       'icon' => '⚽', 'description' => 'Sports and fitness gear'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}