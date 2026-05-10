<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard — StyleHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body{font-family:'DM Sans',sans-serif;}.font-display{font-family:'Syne',sans-serif;}</style>
</head>
<body class="bg-gray-50 min-h-screen">
<div class="flex min-h-screen">
@include('partials.pm_sidebar')
<div class="ml-64 flex-1">
    <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between sticky top-0 z-30">
        <h1 class="font-display font-semibold text-gray-900 text-lg">Dashboard</h1>
        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-sm font-bold">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
    </header>
    <main class="p-6">
        @if(session('success'))<div class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-xl">✅ {{ session('success') }}</div>@endif
        <div class="mb-8"><h2 class="font-display text-2xl font-bold text-gray-900">Hello, {{ auth()->user()->name }}! 👋</h2><p class="text-gray-500 text-sm mt-1">Manage your product inventory from here.</p></div>
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
            @foreach([['label'=>'Total Products','value'=>$stats['total_products'],'icon'=>'📦','color'=>'bg-blue-500'],['label'=>'Low Stock','value'=>$stats['low_stock'],'icon'=>'⚠️','color'=>'bg-amber-500'],['label'=>'Categories','value'=>$stats['categories'],'icon'=>'📁','color'=>'bg-indigo-500'],['label'=>'Out of Stock','value'=>$stats['out_of_stock'],'icon'=>'🚫','color'=>'bg-red-500']] as $card)
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 {{ $card['color'] }} rounded-xl flex items-center justify-center text-2xl">{{ $card['icon'] }}</div>
                <div><p class="text-xs text-gray-500 font-medium">{{ $card['label'] }}</p><p class="text-2xl font-display font-bold text-gray-900">{{ $card['value'] }}</p></div>
            </div>
            @endforeach
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
            <h3 class="font-display font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach([['icon'=>'➕','label'=>'Add Product','href'=>route('product_manager.products.create')],['icon'=>'📋','label'=>'All Products','href'=>route('product_manager.products.index')],['icon'=>'📁','label'=>'Categories','href'=>route('product_manager.categories.index')],['icon'=>'🗂️','label'=>'Add Category','href'=>route('product_manager.categories.create')]] as $a)
                <a href="{{ $a['href'] }}" class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-dashed border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-colors text-center group">
                    <span class="text-3xl">{{ $a['icon'] }}</span><span class="text-xs font-semibold text-gray-600 group-hover:text-blue-700">{{ $a['label'] }}</span>
                </a>
                @endforeach
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4"><h3 class="font-display font-semibold text-gray-900">Recent Products</h3><a href="{{ route('product_manager.products.index') }}" class="text-xs text-blue-600 font-semibold hover:underline">View All →</a></div>
            @if($recent_products->isEmpty())
            <div class="text-center py-12"><div class="text-5xl mb-3">📦</div><p class="font-medium text-gray-500">No products yet</p><a href="{{ route('product_manager.products.create') }}" class="inline-block mt-4 bg-blue-500 text-white text-sm font-semibold px-5 py-2.5 rounded-xl">+ Add Product</a></div>
            @else
            <table class="w-full text-sm"><thead><tr class="border-b border-gray-100"><th class="text-left py-3 px-2 text-xs font-semibold text-gray-500 uppercase">Product</th><th class="text-left py-3 px-2 text-xs font-semibold text-gray-500 uppercase">Category</th><th class="text-left py-3 px-2 text-xs font-semibold text-gray-500 uppercase">Price (UGX)</th><th class="text-left py-3 px-2 text-xs font-semibold text-gray-500 uppercase">Stock</th><th class="text-left py-3 px-2 text-xs font-semibold text-gray-500 uppercase">Status</th><th class="py-3 px-2"></th></tr></thead>
            <tbody class="divide-y divide-gray-50">
            @foreach($recent_products as $p)
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-2 font-medium text-gray-900">{{ $p->name }}</td>
                <td class="py-3 px-2 text-gray-500">{{ $p->category->name }}</td>
                <td class="py-3 px-2">{{ number_format($p->price) }}</td>
                <td class="py-3 px-2 font-medium {{ $p->stock==0?'text-red-600':($p->stock<=5?'text-amber-600':'text-green-600') }}">{{ $p->stock }}</td>
                <td class="py-3 px-2"><span class="px-2 py-1 text-xs rounded-full font-semibold {{ $p->status==='active'?'bg-green-100 text-green-700':'bg-gray-100 text-gray-600' }}">{{ ucfirst($p->status) }}</span></td>
                <td class="py-3 px-2"><a href="{{ route('product_manager.products.edit',$p) }}" class="text-blue-600 text-xs hover:underline">Edit</a></td>
            </tr>
            @endforeach
            </tbody></table>
            @endif
        </div>
    </main>
</div>
</div>
</body>
</html>
