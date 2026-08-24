@extends('layouts.admin')

@section('page-title', 'Discount Coupons & Promotional Engine')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Left: Create Coupon Form (4 Cols) -->
    <div class="lg:col-span-4 space-y-6">
        <div class="admin-glass rounded-3xl p-6 border border-slate-800 space-y-4">
            <h3 class="font-cyber font-bold text-sm text-white uppercase tracking-wider">
                Create Cyber Coupon
            </h3>

            <form action="{{ route('admin.coupons.store') }}" method="POST" class="space-y-4 font-mono text-xs">
                @csrf
                
                <div class="space-y-1.5">
                    <label class="text-slate-300">Coupon Code (Uppercase) *</label>
                    <input type="text" name="code" required placeholder="e.g. EIDMEGA50" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white uppercase focus:outline-none focus:border-cyan-400">
                </div>

                <div class="space-y-1.5">
                    <label class="text-slate-300">Description</label>
                    <input type="text" name="description" placeholder="e.g. 15% discount for Boishakh" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1.5">
                        <label class="text-slate-300">Discount Type *</label>
                        <select name="type" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white">
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Flat (৳)</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-slate-300">Value *</label>
                        <input type="number" step="0.01" name="value" required placeholder="10 or 200" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1.5">
                        <label class="text-slate-300">Min Spend (৳)</label>
                        <input type="number" name="min_spend" value="0" placeholder="1000" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-slate-300">Max Discount (৳)</label>
                        <input type="number" name="max_discount" placeholder="500" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-slate-300">Usage Limit</label>
                    <input type="number" name="usage_limit" placeholder="500" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white">
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider shadow-lg hover:scale-105 transition-all">
                    Create Coupon Code
                </button>
            </form>
        </div>
    </div>

    <!-- Right: Active Coupons List (8 Cols) -->
    <div class="lg:col-span-8 space-y-4">
        <div class="admin-glass rounded-3xl p-6 border border-slate-800 overflow-hidden">
            <h3 class="font-cyber font-bold text-sm text-white uppercase tracking-wider pb-4 border-b border-slate-800">
                Active Promo Vouchers ({{ $coupons->total() }})
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-mono">
                    <thead>
                        <tr class="text-slate-500 border-b border-slate-800 uppercase text-[10px]">
                            <th class="py-3">Code</th>
                            <th class="py-3">Discount</th>
                            <th class="py-3">Min Spend</th>
                            <th class="py-3">Used</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80">
                        @foreach($coupons as $cp)
                            <tr>
                                <td class="py-3">
                                    <span class="font-bold text-cyan-300 px-2 py-0.5 rounded bg-cyan-500/10 border border-cyan-500/30">
                                        {{ $cp->code }}
                                    </span>
                                    <p class="text-[10px] text-slate-400 mt-1">{{ $cp->description }}</p>
                                </td>
                                <td class="py-3 font-bold text-white">
                                    {{ $cp->type === 'percentage' ? $cp->value . '%' : '৳' . $cp->value }}
                                </td>
                                <td class="py-3 text-slate-300">{{ \App\Helpers\BanglaHelper::formatTaka($cp->min_spend) }}</td>
                                <td class="py-3 text-slate-400">{{ $cp->used_count }} / {{ $cp->usage_limit ?: '∞' }}</td>
                                <td class="py-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $cp->is_active ? 'bg-emerald-500/20 text-emerald-300' : 'bg-red-500/20 text-red-300' }}">
                                        {{ $cp->is_active ? 'ACTIVE' : 'INACTIVE' }}
                                    </span>
                                </td>
                                <td class="py-3 text-right">
                                    <form action="{{ route('admin.coupons.destroy', $cp->id) }}" method="POST" onsubmit="return confirm('Delete this coupon?')">
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
                {{ $coupons->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
