@extends('layouts.admin')

@section('page-title', 'কাস্টমার সিআরএম ও ভিআইপি পোর্টাল (Customer CRM & VIP)')

@section('content')
<div class="space-y-8" x-data="{ showAddModal: false, editCustomer: null }">

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="admin-glass rounded-2xl p-6 border border-cyan-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">TOTAL REGISTERED CUSTOMERS</span>
            <p class="font-cyber font-bold text-2xl text-white">{{ $totalCustomers }} জন</p>
            <p class="text-[11px] text-slate-400 font-mono">নিবন্ধিত বাংলাদেশী গ্রাহক</p>
        </div>

        <div class="admin-glass rounded-2xl p-6 border border-emerald-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">ACTIVE SHOPPERS</span>
            <p class="font-cyber font-bold text-2xl text-emerald-400">{{ $activeCustomers }} জন</p>
            <p class="text-[11px] text-slate-400 font-mono">সক্রিয় কেনাকাটাকারী ক্রেতা</p>
        </div>

        <div class="admin-glass rounded-2xl p-6 border border-amber-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">VIP TIER RATIO</span>
            <p class="font-cyber font-bold text-2xl text-amber-300">PLATINUM VIP</p>
            <p class="text-[11px] text-slate-400 font-mono">নিয়মিত কেনাকাটাকারী ক্রেতা</p>
        </div>
    </div>

    <!-- Action & Filter Bar -->
    <div class="admin-glass rounded-2xl p-5 border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.customers.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto font-mono text-xs">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="গ্রাহকের নাম, ফোন, ইমেইল..." 
                   class="bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white focus:outline-none focus:border-cyan-400 w-64">
            
            <select name="status" class="bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none">
                <option value="">সকল স্ট্যাটাস</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>অ্যাক্টিভ (Active)</option>
                <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>ব্লকড (Blocked)</option>
            </select>

            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-cyan-300 font-bold transition-all">
                Search
            </button>
        </form>

        <button @click="showAddModal = true" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider flex items-center justify-center space-x-2 shadow-lg hover:scale-105 transition-all">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            <span>+ নতুন কাস্টমার যুক্ত করুন 👤</span>
        </button>
    </div>

    <!-- Customers Table -->
    <div class="admin-glass rounded-3xl p-6 border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-mono">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800 uppercase text-[10px]">
                        <th class="pb-3">গ্রাহক</th>
                        <th class="pb-3">মোবাইল ও ইমেইল</th>
                        <th class="pb-3">জেলা</th>
                        <th class="pb-3">মোট অর্ডার</th>
                        <th class="pb-3">স্ট্যাটাস</th>
                        <th class="pb-3 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($customers as $c)
                        <tr>
                            <td class="py-3.5 flex items-center space-x-3">
                                <img src="{{ $c->avatar ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}" class="w-9 h-9 rounded-xl object-cover border border-cyan-400/30">
                                <div>
                                    <span class="font-sans font-bold text-white block">{{ $c->name }}</span>
                                    <span class="text-[10px] text-slate-500">Joined: {{ $c->created_at->format('d M, Y') }}</span>
                                </div>
                            </td>
                            <td class="py-3.5">
                                <span class="text-cyan-300 font-bold block">{{ $c->phone ?: 'N/A' }}</span>
                                <span class="text-[11px] text-slate-400">{{ $c->email }}</span>
                            </td>
                            <td class="py-3.5 text-slate-300">
                                {{ $c->district ?: 'Dhaka' }}
                            </td>
                            <td class="py-3.5">
                                <span class="px-2.5 py-1 rounded-lg bg-indigo-500/20 text-indigo-300 font-bold border border-indigo-500/30">
                                    {{ $c->orders_count }} টি অর্ডার
                                </span>
                            </td>
                            <td class="py-3.5">
                                @if(($c->status ?? 'active') === 'active')
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-[10px] font-bold border border-emerald-500/30">
                                        ● ACTIVE
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-red-500/20 text-red-400 text-[10px] font-bold border border-red-500/30">
                                        ● BLOCKED
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <button @click="editCustomer = {
                                        id: {{ $c->id }},
                                        name: '{{ addslashes($c->name) }}',
                                        email: '{{ addslashes($c->email) }}',
                                        phone: '{{ addslashes($c->phone ?: '') }}',
                                        district: '{{ addslashes($c->district ?: 'Dhaka') }}',
                                        address: '{{ addslashes($c->address ?: '') }}',
                                        status: '{{ $c->status ?? 'active' }}'
                                    }" class="p-2 rounded-xl bg-slate-900 border border-slate-700 text-slate-300 hover:text-white hover:border-cyan-400 transition-all" title="এডিট">
                                        <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                    </button>

                                    <form action="{{ route('admin.customers.toggle_status', $c->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 rounded-xl bg-slate-900 border border-slate-700 {{ ($c->status ?? 'active') === 'active' ? 'text-red-400 hover:bg-red-500/20' : 'text-emerald-400 hover:bg-emerald-500/20' }} transition-all" title="{{ ($c->status ?? 'active') === 'active' ? 'ব্লক করুন' : 'অ্যাক্টিভ করুন' }}">
                                            <i data-lucide="{{ ($c->status ?? 'active') === 'active' ? 'user-x' : 'user-check' }}" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('admin.customers.show', $c->id) }}" class="p-2 rounded-xl bg-cyan-500/20 border border-cyan-500/40 text-cyan-300 hover:bg-cyan-500 hover:text-slate-950 transition-all" title="বিস্তারিত প্রোফাইল">
                                        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">কোনো গ্রাহক পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $customers->links() }}
        </div>
    </div>

    <!-- Create Customer Modal -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div @click.away="showAddModal = false" class="admin-glass rounded-3xl p-6 sm:p-8 border border-cyan-500/40 max-w-lg w-full space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="font-cyber font-bold text-sm text-white flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-4 h-4 text-cyan-400"></i>
                    <span>নতুন গ্রাহক প্রোফাইল তৈরি</span>
                </h3>
                <button @click="showAddModal = false" class="text-slate-400 hover:text-white">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form action="{{ route('admin.customers.store') }}" method="POST" class="space-y-4 text-xs font-mono">
                @csrf
                <div class="space-y-1">
                    <label class="text-slate-300 font-bold">পূর্ণ নাম <span class="text-pink-400">*</span></label>
                    <input type="text" name="name" required placeholder="যেমন: তানভীর আহমেদ"
                           class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-cyan-400">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-slate-300 font-bold">মোবাইল নম্বর <span class="text-pink-400">*</span></label>
                        <input type="text" name="phone" required placeholder="017XXXXXXXX" pattern="01[3-9][0-9]{8}"
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-cyan-400 font-mono">
                    </div>

                    <div class="space-y-1">
                        <label class="text-slate-300 font-bold">ইমেইল <span class="text-pink-400">*</span></label>
                        <input type="email" name="email" required placeholder="customer@example.com"
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-cyan-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-slate-300 font-bold">জেলা <span class="text-pink-400">*</span></label>
                        <input type="text" name="district" required placeholder="Dhaka"
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-cyan-400">
                    </div>

                    <div class="space-y-1">
                        <label class="text-slate-300 font-bold">পাসওয়ার্ড (ঐচ্ছিক)</label>
                        <input type="password" name="password" placeholder="ডিফল্ট: 123456"
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-cyan-400">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300 font-bold">সম্পূর্ণ ঠিকানা <span class="text-pink-400">*</span></label>
                    <textarea name="address" required rows="2" placeholder="বাড়ি #, রোড #, এলাকা..."
                              class="w-full bg-slate-950 border border-slate-700 rounded-xl p-3 text-white focus:outline-none focus:border-cyan-400 leading-relaxed"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end space-x-3">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2 rounded-xl bg-slate-900 border border-slate-700 text-slate-300">
                        বাতিল
                    </button>
                    <button type="submit" class="px-6 py-2 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold">
                        কাস্টমার সেভ করুন
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Customer Modal -->
    <div x-show="editCustomer" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div @click.away="editCustomer = null" class="admin-glass rounded-3xl p-6 sm:p-8 border border-purple-500/40 max-w-lg w-full space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="font-cyber font-bold text-sm text-white flex items-center gap-2">
                    <i data-lucide="edit" class="w-4 h-4 text-purple-400"></i>
                    <span>গ্রাহক তথ্য আপডেট</span>
                </h3>
                <button @click="editCustomer = null" class="text-slate-400 hover:text-white">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form :action="'/admin/customers/' + (editCustomer ? editCustomer.id : '')" method="POST" class="space-y-4 text-xs font-mono">
                @csrf
                @method('PUT')

                <div class="space-y-1">
                    <label class="text-slate-300 font-bold">পূর্ণ নাম</label>
                    <input type="text" name="name" x-model="editCustomer.name" required
                           class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-purple-400">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-slate-300 font-bold">মোবাইল নম্বর</label>
                        <input type="text" name="phone" x-model="editCustomer.phone" required pattern="01[3-9][0-9]{8}"
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-purple-400">
                    </div>

                    <div class="space-y-1">
                        <label class="text-slate-300 font-bold">ইমেইল</label>
                        <input type="email" name="email" x-model="editCustomer.email" required
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-purple-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-slate-300 font-bold">জেলা</label>
                        <input type="text" name="district" x-model="editCustomer.district" required
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-purple-400">
                    </div>

                    <div class="space-y-1">
                        <label class="text-slate-300 font-bold">স্ট্যাটাস</label>
                        <select name="status" x-model="editCustomer.status" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-purple-400">
                            <option value="active">অ্যাক্টিভ (Active)</option>
                            <option value="blocked">ব্লকড (Blocked)</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300 font-bold">ঠিকানা</label>
                    <textarea name="address" x-model="editCustomer.address" required rows="2"
                              class="w-full bg-slate-950 border border-slate-700 rounded-xl p-3 text-white focus:outline-none focus:border-purple-400 leading-relaxed"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end space-x-3">
                    <button type="button" @click="editCustomer = null" class="px-4 py-2 rounded-xl bg-slate-900 border border-slate-700 text-slate-300">
                        বাতিল
                    </button>
                    <button type="submit" class="px-6 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold">
                        আপডেট সংরক্ষণ
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
