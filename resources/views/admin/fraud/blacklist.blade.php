@extends('layouts.admin')

@section('page-title', 'আইপি ও ফ্রড ব্লকলিস্ট (Fraud Shield & IP Blocklist)')

@section('content')
<div class="space-y-8" x-data="{ showModal: false }">

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="admin-glass rounded-2xl p-6 border border-pink-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">TOTAL BLOCKED ENTRIES</span>
            <p class="font-cyber font-bold text-2xl text-pink-400">{{ $totalBlocked }} টি নিষিদ্ধ</p>
            <p class="text-[11px] text-slate-400 font-mono">ব্লকলিস্টে থাকা আইপি ও ফোন</p>
        </div>

        <div class="admin-glass rounded-2xl p-6 border border-emerald-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">FRAUD SHIELD ENGINE</span>
            <p class="font-cyber font-bold text-2xl text-emerald-400">● LIVE ACTIVE</p>
            <p class="text-[11px] text-slate-400 font-mono">ফেক অর্ডার ও স্প্যাম প্রতিরোধক</p>
        </div>

        <div class="admin-glass rounded-2xl p-6 border border-cyan-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">PROTECTION SCOPE</span>
            <p class="font-cyber font-bold text-2xl text-cyan-300">AUTO-FIREWALL</p>
            <p class="text-[11px] text-slate-400 font-mono">চেকআউট ও অর্ডার ব্লকিং</p>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="admin-glass rounded-2xl p-5 border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="font-cyber font-bold text-sm text-white">ফেক কাস্টমার ও সন্দেহভাজন আইপি ব্লকলিস্ট</h3>
            <p class="text-xs text-slate-400 font-mono">এখানে থাকা আইপি বা ফোন নম্বর দিয়ে কোনো অর্ডার গ্রহণ করা হবে না</p>
        </div>

        <button @click="showModal = true" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-gradient-to-r from-pink-500 to-rose-600 text-white font-cyber font-bold text-xs uppercase tracking-wider flex items-center justify-center space-x-2 shadow-lg hover:scale-105 transition-all">
            <i data-lucide="shield-alert" class="w-4 h-4"></i>
            <span>নতুন আইপি / ফোন ব্লক করুন 🚫</span>
        </button>
    </div>

    <!-- Blacklist Table -->
    <div class="admin-glass rounded-3xl p-6 border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-mono">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800 uppercase text-[10px]">
                        <th class="pb-3">আইপি অ্যাড্রেস (IP Address)</th>
                        <th class="pb-3">মোবাইল নম্বর</th>
                        <th class="pb-3">ব্লকের কারণ</th>
                        <th class="pb-3">তারিখ</th>
                        <th class="pb-3 text-right">আনব্লক / মুছুন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($blacklists as $b)
                        <tr>
                            <td class="py-3.5 font-bold text-cyan-300">
                                {{ $b->ip_address ?: '-' }}
                            </td>
                            <td class="py-3.5 font-bold text-pink-400">
                                {{ $b->phone_number ?: '-' }}
                            </td>
                            <td class="py-3.5 text-slate-300 font-sans">
                                {{ $b->reason }}
                            </td>
                            <td class="py-3.5 text-slate-500">
                                {{ $b->created_at->format('d M Y, h:i A') }}
                            </td>
                            <td class="py-3.5 text-right">
                                <form action="{{ route('admin.fraud.blacklist.destroy', $b->id) }}" method="POST" onsubmit="return confirm('ব্লকলিস্ট থেকে রিমুভ করতে চান?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs">
                                        আনব্লক করুন
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-500">কোনো ব্লকলিস্টেড আইপি বা ফোন নেই।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-6">
            {{ $blacklists->links() }}
        </div>
    </div>

    <!-- Add Blacklist Modal -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" @click="showModal = false"></div>

        <div class="relative w-full max-w-md bg-slate-900 border border-pink-500/40 rounded-3xl p-6 sm:p-8 shadow-2xl z-10 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="font-cyber font-bold text-base text-white">আইপি বা ফোন নম্বর ব্লক করুন</h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-white">✕</button>
            </div>

            <form action="{{ route('admin.fraud.blacklist.store') }}" method="POST" class="space-y-4 font-mono text-xs">
                @csrf
                
                <div class="space-y-1">
                    <label class="text-slate-300">মোবাইল নম্বর (01XXXXXXXXX)</label>
                    <input type="text" name="phone_number" placeholder="017XXXXXXXX" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300">আইপি অ্যাড্রেস (IP Address)</label>
                    <input type="text" name="ip_address" placeholder="103.114.24.12" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300">ব্লক করার কারণ *</label>
                    <textarea name="reason" rows="2" required placeholder="e.g. Repeatedly returns parcels on COD / Fake order spammer" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white"></textarea>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-pink-500 to-rose-600 text-white font-cyber font-bold text-xs uppercase tracking-wider shadow-lg hover:scale-105 transition-all">
                    ব্লকলিস্টে যুক্ত করুন 🚫
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
