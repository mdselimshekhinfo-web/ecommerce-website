@extends('layouts.admin')

@section('page-title', '১-পেজ ল্যান্ডিং পেজ বিল্ডার (Landing Page Funnels)')

@section('content')
<div class="space-y-8">

    <!-- Top Action Bar -->
    <div class="admin-glass rounded-2xl p-5 border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="font-cyber font-bold text-base text-white">ফেসবুক অ্যাডস ও স্পেশাল প্রোডাক্ট সেলস ফানেল</h3>
            <p class="text-xs text-slate-400 font-mono">নির্দিষ্ট প্রোডাক্টের জন্য হাই-কনভার্টিং ১-পেজ অর্ডার পেজ</p>
        </div>

        <a href="{{ route('admin.landing-pages.create') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider flex items-center space-x-2 shadow-lg hover:scale-105 transition-all">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>নতুন ল্যান্ডিং পেজ তৈরি করুন 🚀</span>
        </a>
    </div>

    <!-- Landing Pages Table -->
    <div class="admin-glass rounded-3xl p-6 border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-mono">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800 uppercase text-[10px]">
                        <th class="pb-3">শিরোনাম ও স্লাগ (URL)</th>
                        <th class="pb-3">প্রোডাক্ট</th>
                        <th class="pb-3">অফার মূল্য</th>
                        <th class="pb-3">রেগুলার মূল্য</th>
                        <th class="pb-3">স্ট্যাটাস</th>
                        <th class="pb-3 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($landingPages as $lp)
                        <tr>
                            <td class="py-3.5">
                                <span class="font-sans font-bold text-white text-sm block">{{ $lp->title }}</span>
                                <a href="{{ route('landing.show', $lp->slug) }}" target="_blank" class="text-cyan-400 hover:underline text-[11px] inline-flex items-center space-x-1 mt-0.5">
                                    <span>/landing/{{ $lp->slug }}</span>
                                    <i data-lucide="external-link" class="w-3 h-3"></i>
                                </a>
                            </td>
                            <td class="py-3.5 text-slate-300">
                                {{ $lp->product ? $lp->product->name : 'Custom Funnel' }}
                            </td>
                            <td class="py-3.5 font-bold text-emerald-400 text-sm">{{ \App\Helpers\BanglaHelper::formatTaka($lp->offer_price) }}</td>
                            <td class="py-3.5 text-slate-500 line-through">{{ $lp->regular_price ? \App\Helpers\BanglaHelper::formatTaka($lp->regular_price) : '-' }}</td>
                            <td class="py-3.5">
                                <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase
                                    {{ $lp->status === 'active' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : 'bg-slate-800 text-slate-400' }}">
                                    {{ $lp->status }}
                                </span>
                            </td>
                            <td class="py-3.5 text-right space-x-2">
                                <a href="{{ route('landing.show', $lp->slug) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 text-xs font-bold inline-block">
                                    ভিজিট পেজ ↗
                                </a>
                                <a href="{{ route('admin.landing-pages.edit', $lp->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold inline-block">
                                    এডিট ✏️
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">কোনো ল্যান্ডিং পেজ তৈরি করা হয়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-6">
            {{ $landingPages->links() }}
        </div>
    </div>

</div>
@endsection
