@extends('layouts.admin')

@section('page-title', 'Add New Cyber Product')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="admin-glass rounded-3xl p-8 border border-slate-800 space-y-6">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
            <h3 class="font-cyber font-bold text-base text-white">CREATE NEW PRODUCT SPEC</h3>
            <a href="{{ route('admin.products.index') }}" class="text-xs text-slate-400 font-mono hover:text-white">← Back to List</a>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Name (EN) -->
                <div class="space-y-1.5">
                    <label class="font-mono text-xs text-slate-300">Product Title (English) *</label>
                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="e.g. CyberBeast ANC Headphones" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                </div>

                <!-- Name (BN) -->
                <div class="space-y-1.5">
                    <label class="font-mono text-xs text-slate-300">Product Title (Bangla)</label>
                    <input type="text" name="name_bn" value="{{ old('name_bn') }}" placeholder="e.g. সাইবারবিস্ট এএনসি হেডফোন" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-cyan-400 font-bn">
                </div>

                <!-- Category -->
                <div class="space-y-1.5">
                    <label class="font-mono text-xs text-slate-300">Category *</label>
                    <select name="category_id" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Brand -->
                <div class="space-y-1.5">
                    <label class="font-mono text-xs text-slate-300">Brand</label>
                    <select name="brand_id" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                        <option value="">No Brand (Generic)</option>
                        @foreach($brands as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Regular Price -->
                <div class="space-y-1.5">
                    <label class="font-mono text-xs text-slate-300">Regular Price (৳ BDT) *</label>
                    <input type="number" step="0.01" name="price" required value="{{ old('price') }}" placeholder="e.g. 3500" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-cyan-400 font-mono">
                </div>

                <!-- Sale Price -->
                <div class="space-y-1.5">
                    <label class="font-mono text-xs text-slate-300">Sale Price (Optional ৳ BDT)</label>
                    <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price') }}" placeholder="e.g. 2950" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-cyan-400 font-mono">
                </div>

                <!-- Stock Qty -->
                <div class="space-y-1.5">
                    <label class="font-mono text-xs text-slate-300">Stock Quantity *</label>
                    <input type="number" name="stock_quantity" required value="{{ old('stock_quantity', 20) }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-cyan-400 font-mono">
                </div>

                <!-- Badge -->
                <div class="space-y-1.5">
                    <label class="font-mono text-xs text-slate-300">Badge (e.g. HOT, NEW, 50% OFF)</label>
                    <input type="text" name="badge" value="{{ old('badge') }}" placeholder="HOT SELLER" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-cyan-400 font-mono">
                </div>
            </div>

            <!-- Thumbnail Image URL -->
            <div class="space-y-1.5">
                <label class="font-mono text-xs text-slate-300">Image Thumbnail URL</label>
                <input type="url" name="thumbnail" value="{{ old('thumbnail') }}" placeholder="https://images.unsplash.com/..." class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-cyan-400 font-mono">
            </div>

            <!-- Short Description -->
            <div class="space-y-1.5">
                <label class="font-mono text-xs text-slate-300">Short Summary</label>
                <textarea name="short_description" rows="2" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">{{ old('short_description') }}</textarea>
            </div>

            <!-- Long Description -->
            <div class="space-y-1.5">
                <label class="font-mono text-xs text-slate-300">Full Description</label>
                <textarea name="description" rows="4" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">{{ old('description') }}</textarea>
            </div>

            <!-- Checkbox Switches -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-xs font-mono">
                <label class="flex items-center space-x-2 text-slate-300 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" class="rounded text-cyan-500">
                    <span>Featured Product</span>
                </label>
                <label class="flex items-center space-x-2 text-slate-300 cursor-pointer">
                    <input type="checkbox" name="is_trending" value="1" class="rounded text-cyan-500">
                    <span>Trending Gear</span>
                </label>
                <label class="flex items-center space-x-2 text-slate-300 cursor-pointer">
                    <input type="checkbox" name="is_flash_deal" value="1" class="rounded text-pink-500">
                    <span class="text-pink-400 font-bold">Flash Deal</span>
                </label>
                <div>
                    <select name="status" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-xs text-white">
                        <option value="active">Active (Visible)</option>
                        <option value="draft">Draft</option>
                        <option value="out_of_stock">Out of Stock</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider shadow-lg hover:scale-105 transition-all">
                Publish Product Spec
            </button>
        </form>

    </div>
</div>
@endsection
