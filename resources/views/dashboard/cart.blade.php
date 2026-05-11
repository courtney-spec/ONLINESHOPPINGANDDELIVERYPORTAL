<x-app-layout>
    <x-slot name="title">My Cart</x-slot>

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
        <h2 class="font-display text-2xl font-bold text-gray-900">My Cart</h2>
        <p class="text-gray-500 text-sm mt-1">Manage your selected items before checkout.</p>
    </div>

    @if(session('success'))
        <div class="mb-5 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-xl">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-5 bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-xl">❌ {{ session('error') }}</div>
    @endif

    @if($items->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10 text-center">
            <div class="text-5xl mb-3">🛒</div>
            <p class="font-semibold text-gray-700">Your cart is empty</p>
            <p class="text-sm text-gray-400 mt-2">Add products from the dashboard to start shopping.</p>
            <a href="{{ route('customer.dashboard') }}"
               class="inline-block mt-5 bg-orange-500 text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-orange-600 transition-colors">
                Continue Shopping
            </a>
        </div>
    @else
        <div class="grid gap-5 lg:grid-cols-[1.5fr_0.9fr]">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                @foreach($items as $item)
                    <div class="flex flex-col gap-4 mb-6 pb-6 border-b border-gray-100">
                        <div class="flex items-start gap-4">
                            @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-24 h-24 object-cover rounded-2xl">
                            @else
                                <div class="w-24 h-24 bg-gray-100 rounded-2xl flex items-center justify-center text-3xl">📦</div>
                            @endif
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $item->product->name }}</h3>
                                <p class="text-sm text-gray-500 mt-1">UGX {{ number_format($item->product->price) }}</p>
                                <p class="text-sm text-gray-500 mt-1">Stock: {{ $item->product->stock }}</p>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <label class="text-sm text-gray-600">Quantity</label>
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('customer.cart.update', $item->product) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ max(1, $item->quantity - 1) }}">
                                        <button type="submit"
                                                class="w-10 h-10 rounded-xl border border-gray-200 bg-white text-lg text-gray-700 hover:border-orange-400 transition-colors {{ $item->quantity <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                            −
                                        </button>
                                    </form>

                                    <span class="w-14 h-10 rounded-xl border border-gray-200 flex items-center justify-center text-sm font-semibold text-gray-900">
                                        {{ $item->quantity }}
                                    </span>

                                    <form action="{{ route('customer.cart.update', $item->product) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ min($item->product->stock, $item->quantity + 1) }}">
                                        <button type="submit"
                                                class="w-10 h-10 rounded-xl border border-gray-200 bg-white text-lg text-gray-700 hover:border-orange-400 transition-colors {{ $item->quantity >= $item->product->stock ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}>
                                            +
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <form action="{{ route('customer.cart.destroy', $item->product) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors">
                                    Remove
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <aside class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 h-fit">
                <p class="text-sm text-gray-500 mb-4">Order Summary</p>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm text-gray-600">Subtotal</span>
                    <span class="font-semibold text-gray-900">UGX {{ number_format($total) }}</span>
                </div>
                <div class="border-t border-gray-100 pt-4 space-y-2">
                    <a href="{{ route('customer.checkout.create') }}"
                       class="block w-full text-center bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold px-4 py-3 rounded-xl transition-colors">
                        ✓ Proceed to Checkout
                    </a>
                    <a href="{{ route('customer.dashboard') }}"
                       class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-4 py-3 rounded-xl transition-colors">
                        Continue Shopping
                    </a>
                </div>
            </aside>
        </div>
    @endif
</x-app-layout>
