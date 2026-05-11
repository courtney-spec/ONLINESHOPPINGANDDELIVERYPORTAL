<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerCartController extends Controller
{
    protected function cart(): array
    {
        return session()->get('cart', []);
    }

    protected function saveCart(array $cart): void
    {
        session()->put('cart', $cart);
    }

    public function index(): View
    {
        $cart = $this->cart();

        $items = collect($cart)
            ->map(function (array $item, $productId) {
                $product = Product::find($productId);

                if (! $product) {
                    return null;
                }

                return (object) [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $product->price * $item['quantity'],
                ];
            })
            ->filter()
            ->values();

        $total = $items->sum('subtotal');

        return view('dashboard.cart', compact('items', 'total'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'sometimes|integer|min:1',
        ]);

        $product = Product::find($data['product_id']);

        if (! $product || $product->stock === 0) {
            return back()->with('error', 'Product is out of stock.');
        }

        $quantity = $data['quantity'] ?? 1;

        $cart = $this->cart();
        $current = $cart[$product->id]['quantity'] ?? 0;
        $newQuantity = min($current + $quantity, $product->stock);

        $cart[$product->id] = ['quantity' => $newQuantity];
        $this->saveCart($cart);

        return back()->with('success', 'Product added to your cart.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($product->stock === 0) {
            return back()->with('error', 'Product is out of stock.');
        }

        $cart = $this->cart();

        if (! isset($cart[$product->id])) {
            return back()->with('error', 'Product not found in cart.');
        }

        $cart[$product->id] = ['quantity' => min($data['quantity'], $product->stock)];
        $this->saveCart($cart);

        return back()->with('success', 'Cart updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $cart = $this->cart();

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            $this->saveCart($cart);
        }

        return back()->with('success', 'Item removed from cart.');
    }
}
