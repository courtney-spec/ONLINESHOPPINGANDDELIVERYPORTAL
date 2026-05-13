<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first(); // Assuming test user exists
        $products = Product::all();

        // Create orders over the last few months
        $orders = [
            [
                'created_at' => Carbon::now()->subMonths(2),
                'items' => [
                    ['product' => $products->where('name', 'iPhone 15')->first(), 'quantity' => 1],
                    ['product' => $products->where('name', 'Nike T-Shirt')->first(), 'quantity' => 2],
                ],
                'status' => 'completed'
            ],
            [
                'created_at' => Carbon::now()->subMonths(1),
                'items' => [
                    ['product' => $products->where('name', 'Samsung Galaxy S24')->first(), 'quantity' => 1],
                    ['product' => $products->where('name', 'Laravel Book')->first(), 'quantity' => 1],
                ],
                'status' => 'completed'
            ],
            [
                'created_at' => Carbon::now()->subDays(15),
                'items' => [
                    ['product' => $products->where('name', 'Football')->first(), 'quantity' => 3],
                ],
                'status' => 'pending'
            ],
            [
                'created_at' => Carbon::now()->subDays(10),
                'items' => [
                    ['product' => $products->where('name', 'MacBook Pro')->first(), 'quantity' => 1],
                ],
                'status' => 'completed'
            ],
            [
                'created_at' => Carbon::now()->subDays(5),
                'items' => [
                    ['product' => $products->where('name', 'Adidas Sneakers')->first(), 'quantity' => 1],
                    ['product' => $products->where('name', 'Garden Hose')->first(), 'quantity' => 1],
                ],
                'status' => 'shipped'
            ],
        ];

        foreach ($orders as $orderData) {
            $total = 0;
            $order = Order::create([
                'user_id' => $user->id,
                'total' => 0, // Will update after calculating
                'status' => $orderData['status'],
                'payment_method' => 'card',
                'shipping_address' => '123 Test Street',
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => '1234567890',
                'created_at' => $orderData['created_at'],
            ]);

            foreach ($orderData['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['product']->price,
                ]);
                $total += $item['product']->price * $item['quantity'];
            }

            $order->update(['total' => $total]);
        }
    }
}
