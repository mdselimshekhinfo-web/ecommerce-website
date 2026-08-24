@extends('layouts.admin')

@section('page-title', 'এসএমএস নোটিফিকেশন হাব (SMS Gateway & Logs)')

@section('content')
<div class="space-y-8">

    <!-- KPI Metric Card -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="admin-glass rounded-2xl p-6 border border-cyan-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">TOTAL SMS DISPATCHED</span>
            <p class="font-cyber font-bold text-2xl text-cyan-300">{{ $totalSent }} টি এসএমএস</p>
            <p class="text-[11px] text-slate-400 font-mono">সফলভাবে প্রেরিত বার্তা</p>
        </div>

        <div class="admin-glass rounded-2xl p-6 border border-emerald-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">GATEWAY STATUS</span>
            <p class="font-cyber font-bold text-2xl text-emerald-400">● LIVE ACTIVE</p>
            <p class="text-[11px] text-slate-400 font-mono">GreenWeb / BulkSMS BD</p>
        </div>

        <div class="admin-glass rounded-2xl p-6 border border-pink-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">AUTOMATION STATUS</span>
            <p class="font-cyber font-bold text-2xl text-pink-400">AUTO-TRIGGER</p>
            <p class="text-[11px] text-slate-400 font-mono">অর্ডার ও কুরিয়ার আপডেটে অটো এসএমএস</p>
        </div>
    </div>

    <!-- 2 Columns: Send SMS & History -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Quick SMS Sender Form (4 Cols) -->
        <div class="lg:col-span-4 space-y-6">
            <div class="admin-glass rounded-3xl p-6 border border-slate-800 space-y-4 font-mono text-xs">
                <h3 class="font-cyber font-bold text-sm text-white uppercase tracking-wider">
                    কাস্টমারকে কাস্টম এসএমএস পাঠান
                </h3>

                <form action="{{ route('admin.sms.send_custom') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="space-y-1">
                        <label class="text-slate-300">মোবাইল নম্বর (01XXXXXXXXX) *</label>
                        <input type="text" name="phone" required placeholder="01711223344" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white focus:outline-none focus:border-cyan-400">
                    </div>

                    <div class="space-y-1">
                        <label class="text-slate-300">মেসেজের বার্তা (Bangla / English) *</label>
                        <textarea name="message" rows="4" required placeholder="e.g. প্রিয় গ্রাহক, NEXUS DOKAN থেকে আপনার জন্য ১০% স্পেশাল ডিসকাউন্ট কুপন: CYBER10" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white focus:outline-none focus:border-cyan-400"></textarea>
                    </div>

                    <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider shadow-lg hover:scale-105 transition-all flex items-center justify-center space-x-2">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        <span>এসএমএস পাঠান 📱</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Right: SMS Logs Table (8 Cols) -->
        <div class="lg:col-span-8 space-y-4">
            <div class="admin-glass rounded-3xl p-6 border border-slate-800 overflow-hidden">
                <h3 class="font-cyber font-bold text-sm text-white uppercase tracking-wider pb-4 border-b border-slate-800">
                    সম্প্রতি প্রেরিত এসএমএস হিস্ট্রি
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs font-mono">
                        <thead>
                            <tr class="text-slate-500 border-b border-slate-800 uppercase text-[10px]">
                                <th class="pb-3">মোবাইল নম্বর</th>
                                <th class="pb-3">মেসেজ টেক্সট</th>
                                <th class="pb-3">স্ট্যাটাস</th>
                                <th class="pb-3 text-right">সময়</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80">
                            @forelse($logs as $log)
                                <tr>
                                    <td class="py-3 font-bold text-cyan-300">{{ $log->phone_number }}</td>
                                    <td class="py-3 text-slate-300 max-w-sm">{{ $log->message }}</td>
                                    <td class="py-3">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-500/20 text-emerald-300">
                                            {{ $log->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right text-slate-500">{{ $log->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-slate-500">কোনো এসএমএস লগ নেই।</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pt-4">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
