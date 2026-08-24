@extends('layouts.admin')

@section('page-title', 'Product Catalog & Inventory Management')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="admin-glass rounded-2xl p-5 flex flex-col sm:flex-row items-center justify-between gap-4 border border-slate-800">
        <form action="{{ route('admin.products.index') }}" method="GET" class="flex items-center space-x-3 w-full sm:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or SKU..." 
                   class="bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-cyan-400 font-mono w-64">
            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-mono font-bold text-cyan-300">
                Filter
            </button>
        </form>

        <a href="{{ route('admin.products.create') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider flex items-center justify-center space-x-2 shadow-lg hover:scale-105 transition-all">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Add Cyber Product</span>
        </a>
    </div>

    <!-- Products Table -->
    <div class="admin-glass rounded-3xl p-6 border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-mono">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800 uppercase text-[10px]">
                        <th class="pb-3">Product</th>
                        <th class="pb-3">SKU</th>
                        <th class="pb-3">Category</th>
                        <th class="pb-3">Regular Price</th>
                        <th class="pb-3">Sale Price</th>
                        <th class="pb-3">Stock</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @foreach($products as $p)
                        <tr>
                            <td class="py-3 flex items-center space-x-3">
                                <img src="{{ $p->thumbnail }}" class="w-10 h-10 object-cover rounded-lg border border-slate-700 shrink-0">
                                <div>
                                    <h4 class="font-sans font-bold text-white text-xs line-clamp-1">{{ $p->name }}</h4>
                                    <span class="text-[10px] text-cyan-400 font-mono">{{ $p->badge ?: 'STANDARD' }}</span>
                                </div>
                            </td>
                            <td class="py-3 text-slate-400">{{ $p->sku }}</td>
                            <td class="py-3 text-slate-300">{{ $p->category->name }}</td>
                            <td class="py-3 text-white font-bold">{{ \App\Helpers\BanglaHelper::formatTaka($p->price) }}</td>
                            <td class="py-3 text-pink-400 font-bold">
                                {{ $p->sale_price ? \App\Helpers\BanglaHelper::formatTaka($p->sale_price) : '-' }}
                            </td>
                            <td class="py-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $p->stock_quantity > 5 ? 'bg-emerald-500/20 text-emerald-300' : 'bg-red-500/20 text-red-300' }}">
                                    {{ $p->stock_quantity }} units
                                </span>
                            </td>
                            <td class="py-3 uppercase text-[10px] text-slate-400">{{ $p->status }}</td>
                            <td class="py-3 text-right space-x-2">
                                <a href="{{ route('admin.products.edit', $p->id) }}" class="p-1.5 rounded bg-slate-800 hover:bg-cyan-500/20 text-cyan-300 inline-block">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded bg-slate-800 hover:bg-red-500/20 text-red-400 inline-block">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pt-6">
            {{ $products->links() }}
        </div>
    </div>

</div>
@endsection
