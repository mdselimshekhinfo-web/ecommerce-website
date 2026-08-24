@extends('layouts.app')

@section('title', 'Cyber Catalog // All Products - NEXUS DOKAN')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Breadcrumb & Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-2 text-xs font-mono text-slate-400 mb-2">
            <a href="{{ route('home') }}" class="hover:text-cyan-400">HOME</a>
            <span>/</span>
            <span class="text-cyan-400 font-semibold">CYBER CATALOG</span>
            @if(request('category'))
                <span>/</span>
                <span class="text-pink-400 uppercase">{{ request('category') }}</span>
            @endif
        </div>
        <h1 class="font-cyber font-black text-3xl text-white tracking-wide">
            EXPLORE CYBER CATALOG
        </h1>
        <p class="text-xs text-slate-400 mt-1">Showing all futuristic tech, audio gear, and lifestyle peripherals.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Left: Filters Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <form action="{{ route('shop.index') }}" method="GET" class="glass-card rounded-2xl p-5 space-y-6" id="filterForm">
                
                <!-- Search -->
                <div class="space-y-2">
                    <label class="font-cyber font-bold text-xs text-cyan-400 uppercase tracking-wider">Search Keywords</label>
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products..." 
                               class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                    </div>
                </div>

                <!-- Categories -->
                <div class="space-y-2.5">
                    <label class="font-cyber font-bold text-xs text-cyan-400 uppercase tracking-wider">Categories</label>
                    <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1 text-xs">
                        <label class="flex items-center space-x-2.5 text-slate-300 hover:text-white cursor-pointer">
                            <input type="radio" name="category" value="" onchange="this.form.submit()" {{ !request('category') ? 'checked' : '' }} class="text-cyan-500 focus:ring-0">
                            <span>All Categories</span>
                        </label>
                        @foreach($categories as $cat)
                            <label class="flex items-center justify-between text-slate-300 hover:text-white cursor-pointer py-0.5">
                                <span class="flex items-center space-x-2">
                                    <input type="radio" name="category" value="{{ $cat->slug }}" onchange="this.form.submit()" {{ request('category') == $cat->slug ? 'checked' : '' }} class="text-cyan-500 focus:ring-0">
                                    <span class="truncate max-w-[140px]">{{ $cat->name }}</span>
                                </span>
                                <span class="text-[10px] font-mono text-slate-500 bg-slate-800 px-1.5 py-0.5 rounded">{{ $cat->products_count }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Brands -->
                <div class="space-y-2.5">
                    <label class="font-cyber font-bold text-xs text-cyan-400 uppercase tracking-wider">Brands</label>
                    <div class="space-y-1.5 max-h-36 overflow-y-auto pr-1 text-xs">
                        @foreach($brands as $brand)
                            <label class="flex items-center justify-between text-slate-300 hover:text-white cursor-pointer py-0.5">
                                <span class="flex items-center space-x-2">
                                    <input type="radio" name="brand" value="{{ $brand->slug }}" onchange="this.form.submit()" {{ request('brand') == $brand->slug ? 'checked' : '' }} class="text-cyan-500 focus:ring-0">
                                    <span>{{ $brand->name }}</span>
                                </span>
                                <span class="text-[10px] font-mono text-slate-500 bg-slate-800 px-1.5 py-0.5 rounded">{{ $brand->products_count }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Price Range (BDT) -->
                <div class="space-y-2.5">
                    <label class="font-cyber font-bold text-xs text-cyan-400 uppercase tracking-wider">Price Range (৳ BDT)</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min ৳" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-xs text-white">
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max ৳" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-xs text-white">
                    </div>
                </div>

                <!-- Quick Checkboxes -->
                <div class="space-y-2 text-xs">
                    <label class="flex items-center space-x-2 text-pink-400 cursor-pointer">
                        <input type="checkbox" name="flash_deals" value="1" onchange="this.form.submit()" {{ request('flash_deals') ? 'checked' : '' }} class="rounded text-pink-500 focus:ring-0">
                        <span class="font-bold flex items-center"><i data-lucide="flame" class="w-3.5 h-3.5 mr-1"></i> Flash Deals Only</span>
                    </label>
                    <label class="flex items-center space-x-2 text-emerald-400 cursor-pointer">
                        <input type="checkbox" name="in_stock" value="1" onchange="this.form.submit()" {{ request('in_stock') ? 'checked' : '' }} class="rounded text-emerald-500 focus:ring-0">
                        <span>In Stock Items Only</span>
                    </label>
                </div>

                <!-- Filter Actions -->
                <div class="flex items-center space-x-2 pt-2">
                    <button type="submit" class="flex-1 py-2.5 rounded-xl cyber-btn text-xs font-bold font-cyber shadow-neon-cyan">
                        Apply Filters
                    </button>
                    <a href="{{ route('shop.index') }}" class="px-3 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white text-xs font-mono">
                        Reset
                    </a>
                </div>

            </form>
        </div>

        <!-- Right: Products Catalog Grid -->
        <div class="lg:col-span-3 space-y-6">
            
            <!-- Top Controls (Count & Sort) -->
            <div class="glass-card rounded-2xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-slate-400 font-mono">
                    Showing <span class="text-cyan-400 font-bold">{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</span> of <span class="text-white font-bold">{{ $products->total() }}</span> Cyber Products
                </p>

                <div class="flex items-center space-x-2 text-xs">
                    <span class="text-slate-400">Sort by:</span>
                    <select name="sort" onchange="document.getElementById('filterForm').elements['sort'].value = this.value; document.getElementById('filterForm').submit()" 
                            class="bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest Arrival</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rating</option>
                    </select>
                </div>
            </div>

            <!-- Products List -->
            @if($products->isEmpty())
                <div class="glass-card rounded-3xl p-16 text-center space-y-4">
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-800/60 flex items-center justify-center border border-slate-700">
                        <i data-lucide="search-x" class="w-8 h-8 text-slate-500"></i>
                    </div>
                    <h3 class="font-cyber font-bold text-lg text-white">No Cyber Products Found</h3>
                    <p class="text-xs text-slate-400 max-w-sm mx-auto">No products matched your search or active filters. Try adjusting your search query or reset filters.</p>
                    <a href="{{ route('shop.index') }}" class="inline-block px-6 py-2.5 rounded-xl cyber-btn text-xs font-bold shadow-neon-cyan">
                        Reset All Filters
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="glass-card rounded-2xl p-4 flex flex-col justify-between relative group">
                            
                            <!-- Badges -->
                            <div class="absolute top-3 left-3 z-10 flex flex-col space-y-1">
                                @if($product->badge)
                                    <span class="px-2 py-0.5 rounded-md bg-cyan-500/20 border border-cyan-400/40 text-cyan-300 font-mono text-[9px] font-bold uppercase">
                                        {{ $product->badge }}
                                    </span>
                                @endif
                                @if($product->discount_percent > 0)
                                    <span class="px-2 py-0.5 rounded-md bg-pink-600 text-white font-mono text-[9px] font-bold uppercase">
                                        -{{ $product->discount_percent }}% OFF
                                    </span>
                                @endif
                            </div>

                            <!-- Product Visual -->
                            <div class="relative overflow-hidden rounded-xl bg-slate-900 mb-3">
                                <a href="{{ route('product.show', $product->slug) }}">
                                    <img src="{{ $product->thumbnail }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                                </a>
                            </div>

                            <!-- Details -->
                            <div class="space-y-1.5 flex-1">
                                <div class="flex items-center justify-between text-[10px] text-slate-400 font-mono">
                                    <span>{{ $product->category->name }}</span>
                                    <span class="text-amber-400 flex items-center">
                                        <i data-lucide="star" class="w-3 h-3 fill-amber-400 mr-0.5"></i> {{ $product->rating }} ({{ $product->reviews_count }})
                                    </span>
                                </div>

                                <a href="{{ route('product.show', $product->slug) }}" class="block font-semibold text-xs sm:text-sm text-white group-hover:text-cyan-300 transition-colors line-clamp-1">
                                    {{ $product->name }}
                                </a>

                                <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed">
                                    {{ $product->short_description }}
                                </p>

                                <!-- Pricing in ৳ BDT -->
                                <div class="flex items-center space-x-2 pt-2">
                                    <span class="font-mono font-bold text-sm sm:text-base text-cyan-300">
                                        {{ \App\Helpers\BanglaHelper::formatTaka($product->effective_price) }}
                                    </span>
                                    @if($product->sale_price)
                                        <span class="font-mono text-xs text-slate-500 line-through">
                                            {{ \App\Helpers\BanglaHelper::formatTaka($product->price) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Quick Action Button -->
                            <form action="{{ route('cart.add') }}" method="POST" class="mt-4" @submit.prevent="quickAddToCart($event)">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="w-full py-2.5 rounded-xl bg-slate-900 border border-cyan-500/30 hover:border-cyan-400 text-xs font-bold text-cyan-300 hover:text-white hover:bg-cyan-500/20 transition-all flex items-center justify-center space-x-1.5">
                                    <i data-lucide="shopping-bag" class="w-3.5 h-3.5"></i>
                                    <span>Add to Cart</span>
                                </button>
                            </form>

                        </div>
                    @endforeach
                </div>

                <!-- Custom Styled Pagination -->
                <div class="pt-6">
                    {{ $products->links() }}
                </div>
            @endif

        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    function quickAddToCart(e) {
        const form = e.target;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const app = Alpine.$data(document.querySelector('[x-data="globalApp()"]'));
                if (app) {
                    app.openCartDrawer();
                }
            }
        });
    }
</script>
@endpush
