@extends('layouts.admin')

@section('page-title', 'স্টাফ ও পারমিশন রোল ম্যানেজমেন্ট (Users & Staff)')

@section('content')
<div class="space-y-8" x-data="{ showAddModal: false, editStaff: null }">

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="admin-glass rounded-2xl p-6 border border-cyan-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">TOTAL ACTIVE STAFF</span>
            <p class="font-cyber font-bold text-2xl text-white">{{ $totalStaff }} জন</p>
            <p class="text-[11px] text-slate-400 font-mono">নিয়োজিত অ্যাডমিন ও ম্যানেজার</p>
        </div>

        <div class="admin-glass rounded-2xl p-6 border border-pink-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">ROLE ACCESS TIERS</span>
            <p class="font-cyber font-bold text-2xl text-pink-400">3 TIERS</p>
            <p class="text-[11px] text-slate-400 font-mono">Super Admin, Manager, Stock Keeper</p>
        </div>

        <div class="admin-glass rounded-2xl p-6 border border-emerald-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">SECURITY & LOGS</span>
            <p class="font-cyber font-bold text-2xl text-emerald-300">● PROTECTED</p>
            <p class="text-[11px] text-slate-400 font-mono">Bcrypt Hash Password Security</p>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="admin-glass rounded-2xl p-5 border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="font-cyber font-bold text-sm text-white">অ্যাডমিন টিম ও সাব-অ্যাডমিন কর্মী তালিকা</h3>
            <p class="text-xs text-slate-400 font-mono">বিভিন্ন রোলে কর্মী নিয়োগ ও অ্যাক্সেস নিয়ন্ত্রণ করুন</p>
        </div>

        <button @click="showAddModal = true" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider flex items-center justify-center space-x-2 shadow-lg hover:scale-105 transition-all">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            <span>নতুন স্টাফ যুক্ত করুন 👥</span>
        </button>
    </div>

    <!-- Staff Table -->
    <div class="admin-glass rounded-3xl p-6 border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-mono">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800 uppercase text-[10px]">
                        <th class="pb-3">স্টাফ সদস্য</th>
                        <th class="pb-3">মোবাইল ও ইমেইল</th>
                        <th class="pb-3">নিযুক্ত রোল (Role)</th>
                        <th class="pb-3">যোগদানের তারিখ</th>
                        <th class="pb-3 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($staffMembers as $st)
                        <tr>
                            <td class="py-3.5 flex items-center space-x-3">
                                <img src="{{ $st->avatar ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}" class="w-9 h-9 rounded-xl object-cover border border-cyan-400/30">
                                <div>
                                    <span class="font-sans font-bold text-white text-sm block">{{ $st->name }}</span>
                                    @if($st->id === auth()->id())
                                        <span class="text-[10px] text-cyan-300 font-bold">(Current You)</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 text-slate-300">
                                <div>{{ $st->phone ?: '-' }}</div>
                                <span class="text-[10px] text-slate-500">{{ $st->email }}</span>
                            </td>
                            <td class="py-3.5">
                                <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase
                                    {{ $st->role === 'admin' ? 'bg-pink-500/20 text-pink-300 border border-pink-500/40' : ($st->role === 'manager' ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40') }}">
                                    {{ $st->role === 'admin' ? '👑 Super Admin' : ($st->role === 'manager' ? '⚡ Order Manager' : '📦 Stock Keeper') }}
                                </span>
                            </td>
                            <td class="py-3.5 text-slate-400">
                                {{ $st->created_at->format('d M Y') }}
                            </td>
                            <td class="py-3.5 text-right space-x-2">
                                @if($st->id !== auth()->id())
                                    <form action="{{ route('admin.staff.destroy', $st->id) }}" method="POST" class="inline-block" onsubmit="return confirm('স্টাফ একাউন্টটি মুছে ফেলতে চান?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-slate-500 hover:text-red-400">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-500">কোনো স্টাফ পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-6">
            {{ $staffMembers->links() }}
        </div>
    </div>

    <!-- Add Staff Modal -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" @click="showAddModal = false"></div>

        <div class="relative w-full max-w-md bg-slate-900 border border-cyan-500/30 rounded-3xl p-6 sm:p-8 shadow-2xl z-10 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="font-cyber font-bold text-base text-white">নতুন স্টাফ অ্যাকাউন্ট তৈরি</h3>
                <button @click="showAddModal = false" class="text-slate-400 hover:text-white">✕</button>
            </div>

            <form action="{{ route('admin.staff.store') }}" method="POST" class="space-y-4 font-mono text-xs">
                @csrf
                
                <div class="space-y-1">
                    <label class="text-slate-300">স্টাফ সদস্যের নাম *</label>
                    <input type="text" name="name" required placeholder="e.g. Mahbub Alam" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300">ইমেইল এড্রেস (লগইন ইমেইল) *</label>
                    <input type="email" name="email" required placeholder="staff@nexusdokan.bd" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300">মোবাইল নম্বর</label>
                    <input type="text" name="phone" placeholder="017XXXXXXXX" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300">স্টাফ রোল ও পারমিশন *</label>
                    <select name="role" required class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white font-bold">
                        <option value="manager">⚡ Order & Courier Manager (অর্ডার ও কুরিয়ার অপারেশন)</option>
                        <option value="staff">📦 Stock & Inventory Keeper (প্রোডাক্ট স্টক ও পারচেজ অর্ডার)</option>
                        <option value="admin">👑 Super Admin (সম্পূর্ণ সিস্টেম অ্যাক্সেস)</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300">লগইন পাসওয়ার্ড *</label>
                    <input type="password" name="password" required placeholder="কমপক্ষে ৬ ডিজিট" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider shadow-lg hover:scale-105 transition-all">
                    স্টাফ অ্যাকাউন্ট তৈরি করুন 👥
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
