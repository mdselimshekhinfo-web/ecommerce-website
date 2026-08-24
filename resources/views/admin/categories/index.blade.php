@extends('layouts.admin')

@section('page-title', 'Cyber Categories Management')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Left: Create Category Form (4 Cols) -->
    <div class="lg:col-span-4 space-y-6">
        <div class="admin-glass rounded-3xl p-6 border border-slate-800 space-y-4">
            <h3 class="font-cyber font-bold text-sm text-white uppercase tracking-wider">
                Add New Category
            </h3>

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4 font-mono text-xs">
                @csrf
                
                <div class="space-y-1.5">
                    <label class="text-slate-300">Category Name (English) *</label>
                    <input type="text" name="name" required placeholder="e.g. Holographic Drones" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white focus:outline-none focus:border-cyan-400">
                </div>

                <div class="space-y-1.5">
                    <label class="text-slate-300">Category Name (Bangla)</label>
                    <input type="text" name="name_bn" placeholder="e.g. হোলোগ্রাফিক ড্রোন" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white focus:outline-none focus:border-cyan-400 font-bn">
                </div>

                <div class="space-y-1.5">
                    <label class="text-slate-300">Lucide Icon (e.g. headphones, watch, cpu, zap)</label>
                    <input type="text" name="icon" placeholder="cpu" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                </div>

                <div class="space-y-1.5">
                    <label class="text-slate-300">Description</label>
                    <textarea name="description" rows="2" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white"></textarea>
                </div>

                <div class="flex items-center space-x-2 text-slate-300">
                    <input type="checkbox" name="is_featured" value="1" class="rounded text-cyan-500">
                    <span>Featured on Home Page</span>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider shadow-lg hover:scale-105 transition-all">
                    Save Category
                </button>
            </form>
        </div>
    </div>

    <!-- Right: Categories List (8 Cols) -->
    <div class="lg:col-span-8 space-y-4">
        <div class="admin-glass rounded-3xl p-6 border border-slate-800 overflow-hidden">
            <h3 class="font-cyber font-bold text-sm text-white uppercase tracking-wider pb-4 border-b border-slate-800">
                Current Catalog Categories ({{ $categories->total() }})
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-mono">
                    <thead>
                        <tr class="text-slate-500 border-b border-slate-800 uppercase text-[10px]">
                            <th class="pb-3">Icon</th>
                            <th class="pb-3">Category Name</th>
                            <th class="pb-3">Bangla Name</th>
                            <th class="pb-3">Products</th>
                            <th class="pb-3">Featured</th>
                            <th class="pb-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80">
                        @foreach($categories as $cat)
                            <tr>
                                <td class="py-3">
                                    <div class="w-8 h-8 rounded-lg bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400">
                                        <i data-lucide="{{ $cat->icon ?: 'folder' }}" class="w-4 h-4"></i>
                                    </div>
                                </td>
                                <td class="py-3 font-bold text-white font-sans">{{ $cat->name }}</td>
                                <td class="py-3 text-slate-300 font-bn">{{ $cat->name_bn ?: '-' }}</td>
                                <td class="py-3 text-cyan-300">{{ $cat->products_count }} products</td>
                                <td class="py-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $cat->is_featured ? 'bg-emerald-500/20 text-emerald-300' : 'bg-slate-800 text-slate-400' }}">
                                        {{ $cat->is_featured ? 'YES' : 'NO' }}
                                    </span>
                                </td>
                                <td class="py-3 text-right">
                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-500 hover:text-red-400 rounded-lg hover:bg-red-500/10">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-4">
                {{ $categories->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
