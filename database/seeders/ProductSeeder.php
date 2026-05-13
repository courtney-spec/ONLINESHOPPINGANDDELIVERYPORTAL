<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        $products = [
            ['name' => 'iPhone 15', 'slug' => 'iphone-15', 'description' => 'Latest iPhone model', 'price' => 1200000, 'stock' => 10, 'category_id' => $categories->where('name', 'Electronics')->first()->id],
            ['name' => 'Samsung Galaxy S24', 'slug' => 'samsung-galaxy-s24', 'description' => 'Android flagship phone', 'price' => 1000000, 'stock' => 15, 'category_id' => $categories->where('name', 'Electronics')->first()->id],
            ['name' => 'MacBook Pro', 'slug' => 'macbook-pro', 'description' => 'Powerful laptop', 'price' => 2500000, 'stock' => 5, 'category_id' => $categories->where('name', 'Electronics')->first()->id],
            ['name' => 'Nike T-Shirt', 'slug' => 'nike-t-shirt', 'description' => 'Comfortable cotton t-shirt', 'price' => 25000, 'stock' => 50, 'category_id' => $categories->where('name', 'Clothing')->first()->id],
            ['name' => 'Adidas Sneakers', 'slug' => 'adidas-sneakers', 'description' => 'Running shoes', 'price' => 80000, 'stock' => 30, 'category_id' => $categories->where('name', 'Clothing')->first()->id],
            ['name' => 'Garden Hose', 'slug' => 'garden-hose', 'description' => '50ft garden hose', 'price' => 15000, 'stock' => 20, 'category_id' => $categories->where('name', 'Home & Garden')->first()->id],
            ['name' => 'Laravel Book', 'slug' => 'laravel-book', 'description' => 'Learn Laravel framework', 'price' => 50000, 'stock' => 25, 'category_id' => $categories->where('name', 'Books')->first()->id],
            ['name' => 'Football', 'slug' => 'football', 'description' => 'Official size football', 'price' => 30000, 'stock' => 40, 'category_id' => $categories->where('name', 'Sports')->first()->id],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
