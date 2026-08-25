@extends('layouts.admin')

@section('page-title', 'স্টাফ ও পারমিশন রোল ম্যানেজমেন্ট (Users & Staff)')

@section('content')
<div class="space-y-8" x-data="{ showAddModal: false, editStaff: null }">

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="admin-glass rounded-2xl p-6 border border-cyan-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">TOTAL ACTIVE STAFF</span>
            <p class="font-cyber font-bold text-2xl text-white">{{ $totalStaff }} জন</p>
            <p class="text-[11px] text-slate-400 font-mono">নিয়োজিত অ্যাডমিন ও সাব-অ্যাডমিন</p>
        </div>

        <div class="admin-glass rounded-2xl p-6 border border-pink-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">ROLE ACCESS TIERS</span>
            <p class="font-cyber font-bold text-2xl text-pink-400">GRANULAR</p>
            <p class="text-[11px] text-slate-400 font-mono">মডিউলভিত্তিক কাস্টম পারমিশন সাপোর্ট</p>
        </div>

        <div class="admin-glass rounded-2xl p-6 border border-emerald-500/30 space-y-1">
            <span class="text-slate-400 font-mono text-xs uppercase">SECURITY & HASH</span>
            <p class="font-cyber font-bold text-2xl text-emerald-300">● SECURED</p>
            <p class="text-[11px] text-slate-400 font-mono">Bcrypt Password & Permission Control</p>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="admin-glass rounded-2xl p-5 border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="font-cyber font-bold text-sm text-white">অ্যাডমিন টিম ও সাব-অ্যাডমিন কর্মী তালিকা</h3>
            <p class="text-xs text-slate-400 font-mono">মডিউলভিত্তিক অ্যাক্সেস নিয়ন্ত্রণ ও কর্মী পরিচালনা করুন</p>
        </div>

        <button @click="showAddModal = true" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider flex items-center justify-center space-x-2 shadow-lg hover:scale-105 transition-all">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            <span>+ নতুন কর্মী নিয়োগ দিন 👥</span>
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
                        <th class="pb-3">মডিউল পারমিশন</th>
                        <th class="pb-3">স্ট্যাটাস</th>
                        <th class="pb-3 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($staffMembers as $st)
                        <tr>
                            <td class="py-3.5 flex items-center space-x-3">
                                <img src="{{ $st->avatar ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}" class="w-9 h-9 rounded-xl object-cover border border-cyan-400/30">
                                <div>
                                    <span class="font-sans font-bold text-white block">{{ $st->name }}</span>
                                    <span class="text-[10px] text-slate-500">Joined: {{ $st->created_at->format('d M, Y') }}</span>
                                </div>
                            </td>
                            <td class="py-3.5">
                                <span class="text-cyan-300 font-bold block">{{ $st->phone ?: 'N/A' }}</span>
                                <span class="text-[11px] text-slate-400">{{ $st->email }}</span>
                            </td>
                            <td class="py-3.5">
                                @if($st->role === 'admin')
                                    <span class="px-2.5 py-1 rounded-lg bg-pink-500/20 text-pink-300 font-bold border border-pink-500/30">SUPER ADMIN</span>
                                @elseif($st->role === 'manager')
                                    <span class="px-2.5 py-1 rounded-lg bg-cyan-500/20 text-cyan-300 font-bold border border-cyan-500/30">MANAGER</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-800 text-slate-300 font-bold border border-slate-700">STAFF AGENT</span>
                                @endif
                            </td>
                            <td class="py-3.5">
                                @if($st->role === 'admin')
                                    <span class="text-pink-400 font-bold text-[11px]">⚡ All Modules Access</span>
                                @else
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($st->permissions ?? [] as $perm)
                                            <span class="px-2 py-0.5 rounded bg-slate-900 border border-slate-800 text-[10px] text-cyan-300">
                                                {{ $availablePermissions[$perm] ?? $perm }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="py-3.5">
                                @if(($st->status ?? 'active') === 'active')
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
                                    <button @click="editStaff = {
                                        id: {{ $st->id }},
                                        name: '{{ addslashes($st->name) }}',
                                        email: '{{ addslashes($st->email) }}',
                                        phone: '{{ addslashes($st->phone ?: '') }}',
                                        role: '{{ $st->role }}',
                                        status: '{{ $st->status ?? 'active' }}',
                                        permissions: {{ json_encode($st->permissions ?? []) }}
                                    }" class="p-2 rounded-xl bg-slate-900 border border-slate-700 text-slate-300 hover:text-white hover:border-cyan-400 transition-all" title="এডিট">
                                        <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                    </button>

                                    @if($st->id !== auth()->id())
                                        <form action="{{ route('admin.staff.destroy', $st->id) }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই কর্মীকে রিমুভ করতে চান?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-xl bg-slate-900 border border-slate-700 text-red-400 hover:bg-red-500/20 transition-all" title="ডিলিট">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">কোনো স্টাফ পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Staff Modal -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div @click.away="showAddModal = false" class="admin-glass rounded-3xl p-6 sm:p-8 border border-cyan-500/40 max-w-lg w-full space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="font-cyber font-bold text-sm text-white flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-4 h-4 text-cyan-400"></i>
                    <span>নতুন কর্মী নিয়োগ ও পারমিশন প্রদান</span>
                </h3>
                <button @click="showAddModal = false" class="text-slate-400 hover:text-white">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form action="{{ route('admin.staff.store') }}" method="POST" class="space-y-4 text-xs font-mono">
                @csrf
                <div class="space-y-1">
                    <label class="text-slate-300 font-bold">কর্মীর পূর্ণ নাম <span class="text-pink-400">*</span></label>
                    <input type="text" name="name" required placeholder="যেমন: রাকিবুল ইসলাম"
                           class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-cyan-400">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-slate-300 font-bold">ইমেইল <span class="text-pink-400">*</span></label>
                        <input type="email" name="email" required placeholder="staff@example.com"
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-cyan-400">
                    </div>

                    <div class="space-y-1">
                        <label class="text-slate-300 font-bold">মোবাইল নম্বর</label>
                        <input type="text" name="phone" placeholder="017XXXXXXXX"
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-cyan-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-slate-300 font-bold">রোল নির্বাচন করুন <span class="text-pink-400">*</span></label>
                        <select name="role" x-data="{ selectedRole: 'staff' }" x-model="selectedRole" required
                                class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-cyan-400">
                            <option value="staff">Staff Agent (নির্দিষ্ট পারমিশন)</option>
                            <option value="manager">Manager (ম্যানেজার)</option>
                            <option value="admin">Super Admin (সম্পূর্ণ অ্যাক্সেস)</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-slate-300 font-bold">পাসওয়ার্ড <span class="text-pink-400">*</span></label>
                        <input type="password" name="password" required placeholder="কমপক্ষে ৬ অক্ষর"
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-cyan-400">
                    </div>
                </div>

                <!-- Granular Permissions Checklist -->
                <div class="space-y-2 pt-1 border-t border-slate-800">
                    <label class="text-slate-300 font-bold block">মডিউল পারমিশন (অনুমোদিত বিভাগসমূহ):</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($availablePermissions as $key => $label)
                            <label class="flex items-center space-x-2 p-2 rounded-xl bg-slate-950 border border-slate-800 text-[11px] text-slate-300 cursor-pointer hover:border-cyan-500/40">
                                <input type="checkbox" name="permissions[]" value="{{ $key }}" class="rounded text-cyan-500 focus:ring-0">
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-end space-x-3">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2 rounded-xl bg-slate-900 border border-slate-700 text-slate-300">
                        বাতিল
                    </button>
                    <button type="submit" class="px-6 py-2 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold">
                        কর্মী সেভ করুন
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Staff Modal -->
    <div x-show="editStaff" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div @click.away="editStaff = null" class="admin-glass rounded-3xl p-6 sm:p-8 border border-purple-500/40 max-w-lg w-full space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="font-cyber font-bold text-sm text-white flex items-center gap-2">
                    <i data-lucide="edit" class="w-4 h-4 text-purple-400"></i>
                    <span>স্টাফ পারমিশন ও রোল আপডেট</span>
                </h3>
                <button @click="editStaff = null" class="text-slate-400 hover:text-white">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form :action="'/admin/staff/' + (editStaff ? editStaff.id : '')" method="POST" class="space-y-4 text-xs font-mono">
                @csrf
                @method('PUT')

                <div class="space-y-1">
                    <label class="text-slate-300 font-bold">কর্মীর পূর্ণ নাম</label>
                    <input type="text" name="name" x-model="editStaff.name" required
                           class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-purple-400">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-slate-300 font-bold">ইমেইল</label>
                        <input type="email" name="email" x-model="editStaff.email" required
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-purple-400">
                    </div>

                    <div class="space-y-1">
                        <label class="text-slate-300 font-bold">মোবাইল নম্বর</label>
                        <input type="text" name="phone" x-model="editStaff.phone"
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-purple-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="space-y-1">
                        <label class="text-slate-300 font-bold">রোল</label>
                        <select name="role" x-model="editStaff.role" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-purple-400">
                            <option value="staff">Staff Agent</option>
                            <option value="manager">Manager</option>
                            <option value="admin">Super Admin</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-slate-300 font-bold">স্ট্যাটাস</label>
                        <select name="status" x-model="editStaff.status" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-purple-400">
                            <option value="active">Active</option>
                            <option value="blocked">Blocked</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-slate-300 font-bold">নতুন পাসওয়ার্ড</label>
                        <input type="password" name="password" placeholder="পরিবর্তন না করলে খালি রাখুন"
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-purple-400">
                    </div>
                </div>

                <!-- Granular Permissions Checklist -->
                <div class="space-y-2 pt-1 border-t border-slate-800" x-show="editStaff && editStaff.role !== 'admin'">
                    <label class="text-slate-300 font-bold block">মডিউল পারমিশন (অনুমোদিত বিভাগসমূহ):</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($availablePermissions as $key => $label)
                            <label class="flex items-center space-x-2 p-2 rounded-xl bg-slate-950 border border-slate-800 text-[11px] text-slate-300 cursor-pointer hover:border-purple-500/40">
                                <input type="checkbox" name="permissions[]" value="{{ $key }}" 
                                       :checked="editStaff && editStaff.permissions && editStaff.permissions.includes('{{ $key }}')"
                                       class="rounded text-purple-500 focus:ring-0">
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-end space-x-3">
                    <button type="button" @click="editStaff = null" class="px-4 py-2 rounded-xl bg-slate-900 border border-slate-700 text-slate-300">
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
