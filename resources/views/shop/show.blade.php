@extends('layouts.app')

@section('title', $product->name . ' // NEXUS DOKAN BD')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="productPage()">

    <!-- Breadcrumb -->
    <div class="flex items-center space-x-2 text-xs font-mono text-slate-400 mb-8">
        <a href="{{ route('home') }}" class="hover:text-cyan-400">HOME</a>
        <span>/</span>
        <a href="{{ route('shop.index') }}" class="hover:text-cyan-400">CATALOG</a>
        <span>/</span>
        <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" class="hover:text-cyan-400 uppercase">{{ $product->category->name }}</a>
        <span>/</span>
        <span class="text-cyan-400 truncate max-w-[200px]">{{ $product->name }}</span>
    </div>

    <!-- Main Product Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        
        <!-- Left: Gallery & Interactive Zoom Preview (5 Cols) -->
        <div class="lg:col-span-5 space-y-4">
            <!-- Main Large Image Preview with Dynamic Magnetic Zoom -->
            <div class="glass-card rounded-3xl p-3 overflow-hidden relative"
                 x-data="{ 
                     zoomed: false, 
                     zoomX: '50%', 
                     zoomY: '50%',
                     handleMouseMove(e) {
                         const rect = e.currentTarget.getBoundingClientRect();
                         const x = ((e.clientX - rect.left) / rect.width) * 100;
                         const y = ((e.clientY - rect.top) / rect.height) * 100;
                         this.zoomX = `${x}%`;
                         this.zoomY = `${y}%`;
                     }
                 }"
                 @mouseenter="zoomed = true" 
                 @mouseleave="zoomed = false"
                 @mousemove="handleMouseMove($event)">
                
                <div class="relative w-full h-96 sm:h-[480px] rounded-2xl overflow-hidden cursor-crosshair bg-slate-950/80 border border-slate-800">
                    <!-- Zoomable Image -->
                    <img :src="activeImage" 
                         :style="zoomed ? `transform: scale(2.3); transform-origin: ${zoomX} ${zoomY};` : 'transform: scale(1); transform-origin: center center;'"
                         class="w-full h-full object-cover transition-transform duration-100 ease-out select-none pointer-events-none">
                    
                    <!-- Hover Zoom Badge Helper -->
                    <div class="absolute bottom-3 right-3 px-3 py-1.5 rounded-full bg-slate-950/85 backdrop-blur-md border border-cyan-500/30 text-[10px] font-mono text-cyan-300 flex items-center space-x-1.5 shadow-lg select-none pointer-events-none transition-opacity duration-200"
                         :class="zoomed ? 'opacity-0' : 'opacity-100'">
                        <i data-lucide="zoom-in" class="w-3.5 h-3.5"></i>
                        <span>{{ \App\Helpers\LocalizationHelper::getLocale() === 'bn' ? 'জুম করতে মাউস রাখুন' : 'Hover to Zoom' }}</span>
                    </div>
                </div>
            </div>

            <!-- Thumbnail Reel -->
            @if($product->images && count($product->images) > 1)
                <div class="flex items-center space-x-3 overflow-x-auto pb-2">
                    @foreach($product->images as $img)
                        <button @click="activeImage = '{{ $img }}'" 
                                :class="activeImage === '{{ $img }}' ? 'border-cyan-400 shadow-neon-cyan' : 'border-slate-800 opacity-60 hover:opacity-100'"
                                class="w-20 h-20 rounded-xl overflow-hidden border-2 transition-all shrink-0 bg-slate-900">
                            <img src="{{ $img }}" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right: Product Information & Purchase Flow (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Category & Badge -->
            <div class="flex items-center space-x-3">
                <span class="px-2.5 py-1 rounded-md bg-cyan-500/20 border border-cyan-400/40 text-cyan-300 font-mono text-[10px] font-bold uppercase">
                    {{ $product->category->name }}
                </span>
                @if($product->badge)
                    <span class="px-2.5 py-1 rounded-md bg-pink-500/20 border border-pink-500/40 text-pink-400 font-mono text-[10px] font-bold uppercase">
                        {{ $product->badge }}
                    </span>
                @endif
                <span class="text-xs font-mono text-slate-400">SKU: <b class="text-white">{{ $product->sku }}</b></span>
            </div>

            <!-- Product Title -->
            <div class="space-y-1">
                <h1 class="font-cyber font-black text-2xl sm:text-3xl text-white tracking-wide leading-tight">
                    {{ $product->name }}
                </h1>
                @if($product->name_bn)
                    <h2 class="text-sm font-bn text-slate-400 font-medium">{{ $product->name_bn }}</h2>
                @endif
            </div>

            <!-- Rating & Sales -->
            <div class="flex items-center space-x-4 text-xs font-mono">
                <div class="flex items-center space-x-1 text-amber-400">
                    <i data-lucide="star" class="w-4 h-4 fill-amber-400"></i>
                    <span class="font-bold text-white">{{ $product->rating }}</span>
                    <span class="text-slate-500">({{ $product->reviews_count }} Verified Reviews)</span>
                </div>
                <span class="text-slate-600">|</span>
                <span class="text-emerald-400 font-bold">{{ $product->sales_count }}+ Ordered in Bangladesh</span>
            </div>

            <!-- Pricing Box in ৳ BDT -->
            <div class="glass-panel p-5 rounded-2xl border border-cyan-500/20 flex items-center justify-between">
                <div>
                    <div class="flex items-baseline space-x-3">
                        <span class="font-mono font-black text-3xl text-cyan-300">
                            {{ \App\Helpers\BanglaHelper::formatTaka($product->effective_price) }}
                        </span>
                        @if($product->sale_price)
                            <span class="font-mono text-sm text-slate-500 line-through">
                                {{ \App\Helpers\BanglaHelper::formatTaka($product->price) }}
                            </span>
                            <span class="px-2 py-0.5 rounded bg-pink-600/80 text-white font-mono text-xs font-bold">
                                SAVE {{ $product->discount_percent }}%
                            </span>
                        @endif
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1 font-mono">Inclusive of all taxes in Bangladesh</p>
                </div>

                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-mono font-bold">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span> In Stock ({{ $product->stock_quantity }})
                    </span>
                </div>
            </div>

            <!-- Short Description -->
            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                {{ $product->short_description }}
            </p>

            <!-- Form: Add to Cart & Buy Now -->
            <form action="{{ route('cart.add') }}" method="POST" class="space-y-6" id="addCartForm">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <!-- Dynamic Variants Selection -->
                @if($product->variants)
                    <div class="space-y-4 pt-2">
                        @foreach($product->variants as $variantGroup)
                            <div class="space-y-2">
                                <label class="font-cyber font-bold text-xs text-white uppercase tracking-wider">
                                    Choose {{ $variantGroup['name'] }}:
                                </label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($variantGroup['options'] as $index => $option)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="variant" value="{{ $variantGroup['name'] }}: {{ $option }}" {{ $index === 0 ? 'checked' : '' }} class="peer sr-only">
                                            <div class="px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-xs font-medium text-slate-300 peer-checked:border-cyan-400 peer-checked:bg-cyan-500/10 peer-checked:text-cyan-300 transition-all hover:border-slate-500">
                                                {{ $option }}
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Quantity & Buttons -->
                <div class="flex flex-col sm:flex-row items-center gap-4 pt-4 border-t border-slate-800">
                    
                    <!-- Qty Stepper -->
                    <div class="flex items-center border border-slate-700 bg-slate-900 rounded-xl p-1 shrink-0 w-full sm:w-auto justify-between sm:justify-start">
                        <button type="button" @click="if (qty > 1) qty--" class="w-9 h-9 rounded-lg hover:bg-slate-800 text-slate-300 font-mono text-base flex items-center justify-center">-</button>
                        <input type="number" name="quantity" x-model="qty" min="1" max="{{ $product->stock_quantity }}" class="w-12 bg-transparent text-center font-mono font-bold text-white text-sm focus:outline-none">
                        <button type="button" @click="if (qty < {{ $product->stock_quantity }}) qty++" class="w-9 h-9 rounded-lg hover:bg-slate-800 text-slate-300 font-mono text-base flex items-center justify-center">+</button>
                    </div>

                    <!-- Add To Cart -->
                    <button type="button" @click="handleAddToCart(false)" class="w-full sm:flex-1 py-3.5 rounded-xl bg-slate-900 border border-cyan-500/40 hover:border-cyan-400 text-cyan-300 hover:text-white hover:bg-cyan-500/20 font-cyber font-bold text-xs uppercase tracking-wider flex items-center justify-center space-x-2 transition-all">
                        <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                        <span>Add To Cart</span>
                    </button>

                    <!-- Instant Checkout (Buy Now) -->
                    <button type="button" @click="handleAddToCart(true)" class="w-full sm:flex-1 py-3.5 rounded-xl cyber-btn font-cyber font-bold text-xs uppercase tracking-wider shadow-neon-cyan flex items-center justify-center space-x-2">
                        <i data-lucide="zap" class="w-4 h-4"></i>
                        <span>Instant Buy Now</span>
                    </button>

                </div>
            </form>

            <!-- Stock Urgency Alert (if <= 5 left) -->
            @if($product->stock_quantity > 0 && $product->stock_quantity <= 5)
                <div class="p-3.5 rounded-2xl bg-amber-500/10 border border-amber-500/40 flex items-center justify-between text-xs font-mono text-amber-300 animate-pulse">
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-ping"></span>
                        <span class="font-bold">🔥 সীমিত স্টক: আর মাত্র {{ $product->stock_quantity }}টি পণ্য বাকি আছে!</span>
                    </div>
                    <span class="text-[10px] text-amber-400/80 uppercase font-bold">Fast Selling</span>
                </div>
            @endif

            <!-- Bangladesh Delivery Charge Calculator -->
            <div class="glass-card rounded-2xl p-4 space-y-3 mt-6 border border-slate-800" x-data="deliveryCalculator()">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2 text-xs font-cyber font-bold text-slate-200">
                        <i data-lucide="map-pin" class="w-4 h-4 text-pink-400"></i>
                        <span>ESTIMATE BD DELIVERY CHARGE</span>
                    </div>
                    <span class="text-[10px] font-mono text-cyan-400" x-text="selectedDistrict.zone === 'inside_dhaka' ? '24 Hours Delivery' : '48-72 Hours Delivery'"></span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <select x-model="selectedDistrictKey" @change="updateDistrict()" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                            @foreach($districts as $key => $d)
                                <option value="{{ $key }}">{{ $key }} ({{ $d['name_bn'] }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="p-2 rounded-xl bg-slate-900/80 border border-slate-800 flex items-center justify-between text-xs font-mono">
                        <span class="text-slate-400">Shipping Cost:</span>
                        <span class="font-bold text-cyan-300" x-text="'৳' + selectedDistrict.cost">৳60</span>
                    </div>
                </div>
            </div>

            <!-- Social Share Buttons -->
            <div class="pt-4 border-t border-slate-800 flex flex-wrap items-center justify-between gap-3">
                <span class="text-xs font-cyber font-bold text-slate-400 uppercase tracking-wider">শেয়ার করুন:</span>
                <div class="flex items-center space-x-2">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" 
                       class="px-3 py-1.5 rounded-xl bg-blue-600/20 text-blue-400 border border-blue-500/30 hover:bg-blue-600 hover:text-white transition-all text-xs font-bold flex items-center space-x-1.5" title="Facebook-এ শেয়ার করুন">
                        <i data-lucide="share-2" class="w-3.5 h-3.5"></i>
                        <span>Facebook</span>
                    </a>
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($product->name . ' - ' . url()->current()) }}" target="_blank"
                       class="px-3 py-1.5 rounded-xl bg-emerald-600/20 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-600 hover:text-white transition-all text-xs font-bold flex items-center space-x-1.5" title="WhatsApp-এ পাঠান">
                        <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                        <span>WhatsApp</span>
                    </a>
                    <button type="button" @click="navigator.clipboard.writeText(window.location.href); alert('লিঙ্কটি সফলভাবে কপি করা হয়েছে!')"
                            class="px-3 py-1.5 rounded-xl bg-slate-900 text-slate-300 border border-slate-700 hover:border-cyan-400 hover:text-cyan-300 transition-all text-xs font-mono flex items-center space-x-1.5" title="Copy Link">
                        <i data-lucide="link" class="w-3.5 h-3.5"></i>
                        <span>কপি লিঙ্ক</span>
                    </button>
                </div>
            </div>

            <!-- Trust Badges -->
            <div class="grid grid-cols-3 gap-3 pt-2 text-center text-[10px] font-mono text-slate-400">
                <div class="p-2.5 rounded-xl bg-slate-900/60 border border-slate-800">
                    <i data-lucide="shield-check" class="w-4 h-4 text-cyan-400 mx-auto mb-1"></i>
                    <span>100% Genuine</span>
                </div>
                <div class="p-2.5 rounded-xl bg-slate-900/60 border border-slate-800">
                    <i data-lucide="rotate-ccw" class="w-4 h-4 text-pink-400 mx-auto mb-1"></i>
                    <span>7 Days Return</span>
                </div>
                <div class="p-2.5 rounded-xl bg-slate-900/60 border border-slate-800">
                    <i data-lucide="credit-card" class="w-4 h-4 text-emerald-400 mx-auto mb-1"></i>
                    <span>bKash / COD</span>
                </div>
            </div>

        </div>

    </div>

    <!-- Product Details & Specs Tabs -->
    <div class="mt-16 glass-card rounded-3xl p-6 sm:p-8" x-data="{ tab: 'specs' }">
        
        <!-- Tab Headers -->
        <div class="flex items-center space-x-4 border-b border-slate-800 pb-4 overflow-x-auto">
            <button @click="tab = 'specs'" :class="tab === 'specs' ? 'text-cyan-400 border-b-2 border-cyan-400 font-bold' : 'text-slate-400 hover:text-white'" class="pb-2 text-xs sm:text-sm font-cyber uppercase tracking-wider transition-all">
                Technical Specs
            </button>
            <button @click="tab = 'description'" :class="tab === 'description' ? 'text-cyan-400 border-b-2 border-cyan-400 font-bold' : 'text-slate-400 hover:text-white'" class="pb-2 text-xs sm:text-sm font-cyber uppercase tracking-wider transition-all">
                Full Description
            </button>
            <button @click="tab = 'reviews'" :class="tab === 'reviews' ? 'text-cyan-400 border-b-2 border-cyan-400 font-bold' : 'text-slate-400 hover:text-white'" class="pb-2 text-xs sm:text-sm font-cyber uppercase tracking-wider transition-all">
                Customer Reviews ({{ $product->reviews_count }})
            </button>
        </div>

        <!-- Tab 1: Specs -->
        <div x-show="tab === 'specs'" class="py-6">
            @if($product->specs)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($product->specs as $key => $val)
                        <div class="p-3.5 rounded-xl bg-slate-900/60 border border-slate-800 flex items-center justify-between text-xs">
                            <span class="text-slate-400 font-mono">{{ $key }}</span>
                            <span class="font-bold text-white text-right">{{ $val }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-400">Detailed hardware specifications are listed in the product description.</p>
            @endif
        </div>

        <!-- Tab 2: Description -->
        <div x-show="tab === 'description'" x-cloak class="py-6 space-y-4 text-xs sm:text-sm text-slate-300 leading-relaxed">
            <p>{{ $product->description }}</p>
            @if($product->description_bn)
                <div class="p-4 rounded-xl bg-slate-900/70 border border-slate-800 font-bn text-slate-200 mt-4 leading-relaxed">
                    <h4 class="font-bold text-cyan-400 text-sm mb-1">বাংলা বিবরণ:</h4>
                    <p>{{ $product->description_bn }}</p>
                </div>
            @endif
        </div>

        <!-- Tab 3: Reviews -->
        <div x-show="tab === 'reviews'" x-cloak class="py-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($product->reviews as $rev)
                    <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800 space-y-2">
                        <div class="flex items-center justify-between">
                            <div>
                                <h5 class="font-bold text-xs text-white">{{ $rev->customer_name }}</h5>
                                <p class="text-[10px] text-slate-400">{{ $rev->customer_location }}</p>
                            </div>
                            <div class="flex items-center space-x-0.5 text-amber-400">
                                @for($i = 0; $i < $rev->rating; $i++)
                                    <i data-lucide="star" class="w-3 h-3 fill-amber-400"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="text-xs text-slate-300 leading-relaxed italic">"{{ $rev->comment }}"</p>
                    </div>
                @empty
                    <p class="text-xs text-slate-400">No reviews yet. Be the first cyber shopper to review this product!</p>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Related Products -->
    @if($relatedProducts->isNotEmpty())
        <div class="mt-16">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-cyber font-bold text-xl text-white flex items-center space-x-2">
                    <i data-lucide="sparkles" class="w-5 h-5 text-cyan-400"></i>
                    <span>SIMILAR CYBER GEAR</span>
                </h3>
                <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" class="text-xs text-cyan-400 hover:text-cyan-300 font-semibold flex items-center space-x-1 hover:underline">
                    <span>View All in {{ $product->category->name }}</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $rel)
                    <div class="glass-card rounded-2xl p-4 flex flex-col justify-between relative group" x-data="{ adding: false, added: false }">
                        <!-- Discount Badge -->
                        @if($rel->discount_percent > 0)
                            <span class="absolute top-3 left-3 z-10 px-2 py-0.5 rounded-md bg-pink-600 text-white font-mono text-[9px] font-bold uppercase">
                                -{{ $rel->discount_percent }}% OFF
                            </span>
                        @endif
                        @if($rel->stock_quantity <= 0)
                            <span class="absolute top-3 left-3 z-10 px-2 py-0.5 rounded-md bg-red-600/80 text-white font-mono text-[9px] font-bold uppercase">
                                Sold Out
                            </span>
                        @endif
                        <a href="{{ route('product.show', $rel->slug) }}" class="block">
                            <img src="{{ $rel->thumbnail }}" class="w-full h-44 object-cover rounded-xl mb-3 group-hover:scale-105 transition-transform duration-500"
                                 @if($rel->stock_quantity <= 0) style="filter: grayscale(0.5) brightness(0.7)" @endif>
                            <div class="flex items-center justify-between text-[10px] text-slate-400 font-mono mb-1">
                                <span>{{ $rel->category->name }}</span>
                                <span class="text-amber-400">★ {{ $rel->rating }}</span>
                            </div>
                            <h4 class="font-semibold text-xs text-white truncate group-hover:text-cyan-300 transition-colors">{{ $rel->name }}</h4>
                            <div class="flex items-center space-x-2 mt-1.5">
                                <span class="font-mono font-bold text-sm text-cyan-300">{{ \App\Helpers\BanglaHelper::formatTaka($rel->effective_price) }}</span>
                                @if($rel->sale_price)
                                    <span class="font-mono text-xs text-slate-500 line-through">{{ \App\Helpers\BanglaHelper::formatTaka($rel->price) }}</span>
                                @endif
                            </div>
                        </a>
                        @if($rel->stock_quantity > 0)
                        <form action="{{ route('cart.add') }}" method="POST" class="mt-3" @submit.prevent="
                            adding = true;
                            fetch($el.action, { method: 'POST', body: new FormData($el), headers: {'X-Requested-With':'XMLHttpRequest'} })
                            .then(r => r.json()).then(d => {
                                adding = false;
                                if(d.success) { added = true; setTimeout(() => added = false, 2000); const app = Alpine.$data(document.querySelector('[x-data]')); if(app && app.openCartDrawer) app.openCartDrawer(); }
                            }).catch(() => adding = false);
                        ">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $rel->id }}">
                            <button type="submit" :disabled="adding"
                                    class="w-full py-2 rounded-xl text-[11px] font-bold transition-all flex items-center justify-center space-x-1.5"
                                    :class="added ? 'bg-emerald-500/20 border border-emerald-400 text-emerald-300' : 'bg-slate-900 border border-slate-700 hover:border-cyan-400 text-slate-400 hover:text-cyan-300'">
                                <i :data-lucide="added ? 'check' : 'shopping-bag'" class="w-3 h-3"></i>
                                <span x-text="adding ? 'Adding...' : (added ? 'Added ✓' : 'Add to Cart')">Add to Cart</span>
                            </button>
                        </form>
                        @else
                        <div class="mt-3 w-full py-2 rounded-xl text-[11px] font-bold bg-slate-900/40 border border-slate-800 text-slate-600 flex items-center justify-center">
                            Sold Out
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Customer Reviews & Rating Section -->
    <div class="glass-panel rounded-3xl p-6 sm:p-10 border border-slate-800 space-y-8 mt-12">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-800">
            <div>
                <h3 class="font-cyber font-bold text-xl text-white flex items-center space-x-2">
                    <i data-lucide="star" class="w-5 h-5 text-amber-400"></i>
                    <span>গ্রাহকদের রিভিউ ও স্টার রেটিং ({{ $product->reviews->count() }})</span>
                </h3>
                <p class="text-xs text-slate-400 font-mono">যাচাইকৃত ক্রেতাদের বাস্তব অভিজ্ঞতা ও মতামত</p>
            </div>
            
            <div class="flex items-center space-x-2 text-amber-400 font-bold text-sm">
                <span class="text-2xl font-black text-white">4.9</span>
                <span>★★★★★</span>
                <span class="text-xs text-slate-400 font-mono">({{ $product->reviews->count() }} Reviews)</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left: Reviews List (7 Cols) -->
            <div class="lg:col-span-7 space-y-4">
                @forelse($product->reviews as $rev)
                    <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800 space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <span class="font-bold text-white text-sm">{{ $rev->reviewer_name }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                    ✓ Verified Buyer
                                </span>
                            </div>
                            <span class="text-amber-400 text-xs font-bold">
                                @for($i = 0; $i < $rev->rating; $i++) ★ @endfor
                            </span>
                        </div>
                        <p class="text-xs text-slate-300 leading-relaxed">{{ $rev->comment }}</p>
                        <span class="text-[10px] text-slate-500 font-mono">{{ $rev->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <div class="p-8 rounded-2xl bg-slate-900/40 border border-slate-800 text-center text-xs text-slate-500">
                        এই প্রোডাক্টে এখনো কোনো রিভিউ নেই। আপনিই প্রথম রিভিউ দিন! ⭐
                    </div>
                @endforelse
            </div>

            <!-- Right: Submit Review Form (5 Cols) -->
            <div class="lg:col-span-5 p-6 rounded-2xl bg-slate-900/80 border border-cyan-500/30 space-y-4 font-mono text-xs">
                <h4 class="font-cyber font-bold text-sm text-white uppercase tracking-wider">
                    আপনার রিভিউ লিখুন ⭐
                </h4>

                <form action="{{ route('product.review.submit', $product->slug) }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="space-y-1">
                        <label class="text-slate-300">আপনার নাম *</label>
                        <input type="text" name="reviewer_name" value="{{ auth()->user()->name ?? '' }}" required placeholder="e.g. Tanvir Hasan" 
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white focus:outline-none focus:border-cyan-400">
                    </div>

                    <div class="space-y-1">
                        <label class="text-slate-300">মোবাইল নম্বর (ঐচ্ছিক)</label>
                        <input type="text" name="reviewer_phone" value="{{ auth()->user()->phone ?? '' }}" placeholder="017XXXXXXXX" 
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                    </div>

                    <div class="space-y-1">
                        <label class="text-slate-300">স্টার রেটিং *</label>
                        <select name="rating" required class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-amber-400 font-bold focus:outline-none focus:border-cyan-400">
                            <option value="5">★★★★★ (৫ স্টার - অসাধারণ)</option>
                            <option value="4">★★★★☆ (৪ স্টার - ভালো)</option>
                            <option value="3">★★★☆☆ (৩ স্টার - চলনসই)</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-slate-300">আপনার মতামত / রিভিউ *</label>
                        <textarea name="comment" rows="3" required placeholder="প্রোডাক্টটি আপনার কেমন লেগেছে লিখুন..." 
                                  class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white font-sans text-xs focus:outline-none focus:border-cyan-400"></textarea>
                    </div>

                    <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider shadow-lg hover:scale-105 transition-all">
                        রিভিউ সাবমিট করুন ⭐
                    </button>
                </form>
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function productPage() {
        return {
            activeImage: '{{ $product->thumbnail }}',
            qty: 1,

            handleAddToCart(isBuyNow) {
                const form = document.getElementById('addCartForm');
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
                        if (isBuyNow) {
                            window.location.href = '{{ route("checkout.index") }}';
                        } else {
                            const app = Alpine.$data(document.querySelector('[x-data="globalApp()"]'));
                            if (app) {
                                app.openCartDrawer();
                            }
                        }
                    }
                });
            }
        }
    }

    function deliveryCalculator() {
        return {
            districts: @json($districts),
            selectedDistrictKey: 'Dhaka',
            selectedDistrict: { name_bn: 'ঢাকা', zone: 'inside_dhaka', cost: 60 },

            updateDistrict() {
                this.selectedDistrict = this.districts[this.selectedDistrictKey] || { zone: 'outside_dhaka', cost: 120 };
            }
        }
    }
</script>
@endpush
