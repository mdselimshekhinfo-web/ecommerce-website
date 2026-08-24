@extends('layouts.admin')

@section('page-title', 'পলিসি ও কনটেন্ট পেজ ম্যানেজার (Policy & Custom Pages)')

@section('content')
<div class="space-y-8">

    <!-- Top Action Bar -->
    <div class="admin-glass rounded-2xl p-5 border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="font-cyber font-bold text-base text-white">ওয়েবসাইট পলিসি, শর্তাবলী ও লিগ্যাল পেজ</h3>
            <p class="text-xs text-slate-400 font-mono">রিটার্ন পলিসি, শিপিং টার্মস, প্রাইভেসি পলিসি ইত্যাদি নিয়ন্ত্রণ করুন</p>
        </div>

        <a href="{{ route('admin.pages.create') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider flex items-center space-x-2 shadow-lg hover:scale-105 transition-all">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>নতুন পলিসি পেজ তৈরি করুন 📜</span>
        </a>
    </div>

    <!-- Pages Table -->
    <div class="admin-glass rounded-3xl p-6 border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-mono">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800 uppercase text-[10px]">
                        <th class="pb-3">পেজের নাম</th>
                        <th class="pb-3">URL স্লাগ</th>
                        <th class="pb-3">ফুটার লিংক</th>
                        <th class="pb-3">স্ট্যাটাস</th>
                        <th class="pb-3 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($pages as $pg)
                        <tr>
                            <td class="py-3.5 font-bold text-white font-sans text-sm">
                                {{ $pg->title }}
                            </td>
                            <td class="py-3.5">
                                <a href="{{ route('page.show', $pg->slug) }}" target="_blank" class="text-cyan-400 hover:underline inline-flex items-center space-x-1">
                                    <span>/page/{{ $pg->slug }}</span>
                                    <i data-lucide="external-link" class="w-3 h-3"></i>
                                </a>
                            </td>
                            <td class="py-3.5 text-slate-300">
                                {{ $pg->is_footer_link ? '✓ Yes' : 'No' }}
                            </td>
                            <td class="py-3.5">
                                <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase
                                    {{ $pg->status === 'published' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : 'bg-slate-800 text-slate-400' }}">
                                    {{ $pg->status }}
                                </span>
                            </td>
                            <td class="py-3.5 text-right space-x-2">
                                <a href="{{ route('page.show', $pg->slug) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 text-xs font-bold inline-block">
                                    ভিজিট ↗
                                </a>
                                <a href="{{ route('admin.pages.edit', $pg->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold inline-block">
                                    এডিট ✏️
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-500">কোনো পলিসি পেজ নেই।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
