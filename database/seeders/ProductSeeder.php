<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $electronics = Category::where('name', 'Electronics')->first();
        $clothing    = Category::where('name', 'Clothing')->first();
        $food        = Category::where('name', 'Food')->first();

        $products = [
            ['name' => 'Samsung Galaxy A55',  'category_id' => $electronics->id, 'price' => 1200000, 'stock' => 20, 'status' => 'active', 'description' => '6.6 inch display, 8GB RAM'],
            ['name' => 'Tecno Spark 20',      'category_id' => $electronics->id, 'price' => 650000,  'stock' => 15, 'status' => 'active', 'description' => 'Budget smartphone'],
            ['name' => 'HP Laptop 15',        'category_id' => $electronics->id, 'price' => 2500000, 'stock' => 8,  'status' => 'active', 'description' => 'Intel Core i5, 8GB RAM'],
            ['name' => 'Men\'s Polo Shirt',   'category_id' => $clothing->id,    'price' => 45000,   'stock' => 50, 'status' => 'active', 'description' => 'Cotton polo shirt'],
            ['name' => 'Ladies Summer Dress', 'category_id' => $clothing->id,    'price' => 85000,   'stock' => 30, 'status' => 'active', 'description' => 'Floral summer dress'],
            ['name' => 'Basmati Rice 5kg',    'category_id' => $food->id,        'price' => 32000,   'stock' => 100,'status' => 'active', 'description' => 'Premium basmati rice'],
            ['name' => 'Cooking Oil 2L',      'category_id' => $food->id,        'price' => 18000,   'stock' => 3,  'status' => 'active', 'description' => 'Refined sunflower oil'],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}