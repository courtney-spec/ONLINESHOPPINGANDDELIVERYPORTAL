<x-app-layout>
    <x-slot name="title">Checkout</x-slot>

    <x-slot name="sidebar">
        @php
            $links = [
                ['href' => route('customer.dashboard'), 'icon' => '🏠', 'label' => 'My Dashboard'],
                ['href' => route('customer.cart.index'), 'icon' => '🛒', 'label' => 'My Cart'],
                ['href' => route('customer.orders.index'), 'icon' => '📦', 'label' => 'My Orders'],
                ['href' => '#', 'icon' => '🚚', 'label' => 'Track Delivery'],
                ['href' => '#', 'icon' => '❤️', 'label' => 'Wishlist'],
                ['href' => '#', 'icon' => '👤', 'label' => 'My Profile'],
            ];
        @endphp
        @foreach($links as $link)
            <a href="{{ $link['href'] }}"
               class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl transition-colors
                      {{ request()->url() === $link['href'] ? 'bg-orange-50 text-orange-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                <span>{{ $link['icon'] }}</span>
                {{ $link['label'] }}
            </a>
        @endforeach
    </x-slot>

    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold text-gray-900">Checkout</h2>
        <p class="text-gray-500 text-sm mt-1">Complete your purchase by providing your details.</p>
    </div>

    @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-xl">
            <strong>❌ Errors:</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[1.5fr_0.9fr]">
        {{-- Checkout Form --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <form action="{{ route('customer.checkout.store') }}" method="POST" class="space-y-5">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                        <input type="text" name="full_name" value="{{ $user->name }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                        <input type="email" name="email" value="{{ $user->email }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Phone *</label>
                        <input type="tel" name="phone" placeholder="+256..." required
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">City *</label>
                        <input type="text" name="city" placeholder="Kampala" required
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Address *</label>
                        <input type="text" name="address" placeholder="Street address" required
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Postal Code *</label>
                        <input type="text" name="postal_code" placeholder="00256" required
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Payment Method *</label>
                    <select name="payment_method" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                        <option value="">Select a payment method</option>
                        <option value="cash_on_delivery">💵 Cash on Delivery</option>
                        <option value="mobile_money">📱 Mobile Money</option>
                        <option value="card">💳 Credit/Debit Card</option>
                    </select>
                </div>

                <div class="flex gap-3 pt-4">
                    <a href="{{ route('customer.cart.index') }}"
                       class="flex-1 text-center px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-colors">
                        Back to Cart
                    </a>
                    <button type="submit"
                            class="flex-1 px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-xl transition-colors">
                        ✓ Place Order
                    </button>
                </div>
            </form>
        </div>

        {{-- Order Summary --}}
        <aside class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 h-fit">
            <p class="text-sm text-gray-500 mb-4 font-semibold">Order Summary</p>

            <div class="space-y-2 mb-4 pb-4 border-b border-gray-100">
                @foreach($items as $item)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-700">{{ $item->product->name }} × {{ $item->quantity }}</span>
                        <span class="font-semibold text-gray-900">UGX {{ number_format($item->subtotal) }}</span>
                    </div>
                @endforeach
            </div>

            <div class="space-y-2">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-semibold text-gray-900">UGX {{ number_format($total) }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Shipping</span>
                    <span class="font-semibold text-gray-900">Free</span>
                </div>
                <div class="border-t border-gray-100 pt-2 mt-2 flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-700">Total</span>
                    <span class="text-lg font-bold text-green-600">UGX {{ number_format($total) }}</span>
                </div>
            </div>
        </aside>
    </div>
</x-app-layout>
