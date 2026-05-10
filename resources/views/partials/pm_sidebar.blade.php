<aside class="w-64 bg-white border-r border-gray-100 shadow-sm flex flex-col fixed inset-y-0 left-0 z-40">
    <div class="flex items-center gap-2 px-6 py-5 border-b border-gray-100">
        <span class="text-2xl">🛒</span>
        <span class="font-display font-bold text-xl text-gray-900">StyleHub</span>
    </div>
    <div class="px-6 py-4 border-b border-gray-100">
        <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full bg-blue-100 text-blue-700 mb-2">📦 Product Manager</span>
        <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
        <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
    </div>
    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
        <a href="{{ route('product_manager.dashboard') }}"
           class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('product_manager.dashboard') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
           <span>📊</span> Dashboard
        </a>
        <a href="{{ route('product_manager.products.index') }}"
           class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('product_manager.products.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
           <span>📦</span> All Products
        </a>
        <a href="{{ route('product_manager.products.create') }}"
           class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('product_manager.products.create') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
           <span>➕</span> Add Product
        </a>
        <a href="{{ route('product_manager.categories.index') }}"
           class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('product_manager.categories.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
           <span>📁</span> Categories
        </a>
        <a href="{{ route('product_manager.categories.create') }}"
           class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl transition-colors text-gray-600 hover:bg-gray-50">
           <span>🗂️</span> Add Category
        </a>
    </nav>
    <div class="p-4 border-t border-gray-100">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Sign Out
            </button>
        </form>
    </div>
</aside>
