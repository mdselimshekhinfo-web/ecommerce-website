@extends('layouts.admin')

@section('page-title', 'কাস্টমার রিভিউ ও টেস্টিমোনিয়াল (Customer Reviews & Ratings)')

@section('content')
<div class="space-y-8" x-data="{ showModal: false }">

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="admin-glass rounded-2xl p-6 border border-cyan-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">TOTAL REVIEWS</span>
            <p class="font-cyber font-bold text-2xl text-white">{{ $totalReviews }} টি রিভিউ</p>
            <p class="text-[11px] text-slate-400 font-mono">মোট গ্রাহক মতামত</p>
        </div>

        <div class="admin-glass rounded-2xl p-6 border border-amber-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">PENDING APPROVAL</span>
            <p class="font-cyber font-bold text-2xl text-amber-400">{{ $pendingReviews }} টি পেন্ডিং</p>
            <p class="text-[11px] text-slate-400 font-mono">অনুমোদনের অপেক্ষায়</p>
        </div>

        <div class="admin-glass rounded-2xl p-6 border border-emerald-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">AVERAGE RATING</span>
            <p class="font-cyber font-bold text-2xl text-emerald-300">4.9 ★★★★★</p>
            <p class="text-[11px] text-slate-400 font-mono">কাস্টমার সন্তুষ্টি রেটিং</p>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="admin-glass rounded-2xl p-5 border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.reviews.index') }}" method="GET" class="flex items-center space-x-3 w-full sm:w-auto font-mono text-xs">
            <select name="status" class="bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white focus:outline-none">
                <option value="">সকল রিভিউ</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>✓ অনুমোদিত (Approved)</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ পেন্ডিং (Pending)</option>
            </select>
            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-cyan-300 font-bold">
                Filter
            </button>
        </form>

        <button @click="showModal = true" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider flex items-center justify-center space-x-2 shadow-lg hover:scale-105 transition-all">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>নতুন রিভিউ যোগ করুন (ফেসবুক/ম্যানুয়াল) ⭐</span>
        </button>
    </div>

    <!-- Reviews Table -->
    <div class="admin-glass rounded-3xl p-6 border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-mono">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800 uppercase text-[10px]">
                        <th class="pb-3">প্রোডাক্ট</th>
                        <th class="pb-3">গ্রাহকের নাম ও ফোন</th>
                        <th class="pb-3">স্টার রেটিং</th>
                        <th class="pb-3">মন্তব্য / রিভিউ</th>
                        <th class="pb-3">স্ট্যাটাস</th>
                        <th class="pb-3 text-right">অনুমোদন / অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($reviews as $rev)
                        <tr>
                            <td class="py-3.5 font-bold text-white font-sans">
                                {{ $rev->product ? $rev->product->name : 'N/A' }}
                            </td>
                            <td class="py-3.5 text-slate-300 font-sans">
                                <span class="font-bold block text-white">{{ $rev->reviewer_name }}</span>
                                <span class="text-[10px] text-slate-500 font-mono">{{ $rev->reviewer_phone ?: '-' }}</span>
                            </td>
                            <td class="py-3.5 text-amber-400 font-bold">
                                @for($i = 0; $i < $rev->rating; $i++) ★ @endfor
                            </td>
                            <td class="py-3.5 text-slate-300 font-sans max-w-sm">
                                {{ $rev->comment }}
                            </td>
                            <td class="py-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                    {{ $rev->status === 'approved' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : 'bg-amber-500/20 text-amber-300 border border-amber-500/40' }}">
                                    {{ $rev->status }}
                                </span>
                            </td>
                            <td class="py-3.5 text-right space-x-1">
                                @if($rev->status === 'pending')
                                    <form action="{{ route('admin.reviews.update_status', $rev->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="px-2.5 py-1 rounded bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/40 text-[11px] font-bold">
                                            Approve ✓
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.reviews.destroy', $rev->id) }}" method="POST" class="inline-block" onsubmit="return confirm('রিভিউটি ডিলিট করতে চান?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-slate-500 hover:text-red-400">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">কোনো রিভিউ পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-6">
            {{ $reviews->links() }}
        </div>
    </div>

    <!-- Add Review Modal -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" @click="showModal = false"></div>

        <div class="relative w-full max-w-lg bg-slate-900 border border-cyan-500/30 rounded-3xl p-6 sm:p-8 shadow-2xl z-10 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="font-cyber font-bold text-base text-white">নতুন কাস্টমার রিভিউ যুক্ত করুন</h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-white">✕</button>
            </div>

            <form action="{{ route('admin.reviews.store') }}" method="POST" class="space-y-4 font-mono text-xs">
                @csrf
                
                <div class="space-y-1">
                    <label class="text-slate-300">প্রোডাক্ট নির্বাচন করুন *</label>
                    <select name="product_id" required class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white">
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-slate-300">গ্রাহকের নাম *</label>
                        <input type="text" name="reviewer_name" required placeholder="e.g. Tanvir Hasan" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                    </div>
                    <div class="space-y-1">
                        <label class="text-slate-300">স্টার রেটিং (1 to 5) *</label>
                        <select name="rating" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white font-bold">
                            <option value="5">★★★★★ (5 Star)</option>
                            <option value="4">★★★★☆ (4 Star)</option>
                            <option value="3">★★★☆☆ (3 Star)</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300">মন্তব্য / রিভিউ টেক্সট *</label>
                    <textarea name="comment" rows="3" required placeholder="কাস্টমারের রিভিউটি এখানে লিখুন..." class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white"></textarea>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider shadow-lg hover:scale-105 transition-all">
                    রিভিউ সেভ করুন ⭐
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
