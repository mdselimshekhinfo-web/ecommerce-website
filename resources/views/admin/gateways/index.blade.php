@extends('layouts.admin')

@section('page-title', 'মডিউলার গেটওয়ে ও ইন্টিগ্রেশন হাব (Gateway & API Hub)')

@section('content')
<div class="space-y-8" x-data="{ currentTab: '{{ request()->get('tab', 'payment') }}', showAddModal: false, customFields: [{ key: '', value: '' }] }">

    <!-- Top Action Banner -->
    <div class="admin-glass rounded-3xl p-6 border border-cyan-500/30 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-cyber font-bold text-lg text-white flex items-center space-x-2">
                <i data-lucide="cpu" class="w-5 h-5 text-cyan-400"></i>
                <span>মডিউলার গেটওয়ে ও এপিআই কন্ট্রোল সেন্টার</span>
            </h2>
            <p class="text-xs text-slate-400 font-mono mt-1">প্রতিটি পেমেন্ট, কুরিয়ার ও এসএমএস গেটওয়ে আলাদা আলাদা কনফিগার, অন/অফ ও টেস্ট করুন</p>
        </div>

        <button @click="showAddModal = true" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider flex items-center justify-center space-x-2 shadow-lg hover:scale-105 transition-all">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>+ নতুন গেটওয়ে যোগ করুন 🔌</span>
        </button>
    </div>

    <!-- Category Tabs -->
    <div class="flex items-center space-x-3 overflow-x-auto pb-2 border-b border-slate-800">
        <button @click="currentTab = 'payment'" 
                :class="currentTab === 'payment' ? 'bg-cyan-500 text-slate-950 font-bold shadow-neon-cyan' : 'bg-slate-900 border border-slate-700 text-slate-300 hover:text-white'" 
                class="px-5 py-2.5 rounded-xl font-cyber text-xs uppercase tracking-wider transition-all flex items-center space-x-2">
            <i data-lucide="credit-card" class="w-4 h-4"></i>
            <span>💳 পেমেন্ট গেটওয়ে ({{ $paymentGateways->count() }})</span>
        </button>

        <button @click="currentTab = 'courier'" 
                :class="currentTab === 'courier' ? 'bg-cyan-500 text-slate-950 font-bold shadow-neon-cyan' : 'bg-slate-900 border border-slate-700 text-slate-300 hover:text-white'" 
                class="px-5 py-2.5 rounded-xl font-cyber text-xs uppercase tracking-wider transition-all flex items-center space-x-2">
            <i data-lucide="truck" class="w-4 h-4"></i>
            <span>🚚 কুরিয়ার এপিআই ({{ $courierGateways->count() }})</span>
        </button>

        <button @click="currentTab = 'sms'" 
                :class="currentTab === 'sms' ? 'bg-cyan-500 text-slate-950 font-bold shadow-neon-cyan' : 'bg-slate-900 border border-slate-700 text-slate-300 hover:text-white'" 
                class="px-5 py-2.5 rounded-xl font-cyber text-xs uppercase tracking-wider transition-all flex items-center space-x-2">
            <i data-lucide="message-square" class="w-4 h-4"></i>
            <span>📱 এসএমএস গেটওয়ে ({{ $smsGateways->count() }})</span>
        </button>

        <button @click="currentTab = 'other'" 
                :class="currentTab === 'other' ? 'bg-cyan-500 text-slate-950 font-bold shadow-neon-cyan' : 'bg-slate-900 border border-slate-700 text-slate-300 hover:text-white'" 
                class="px-5 py-2.5 rounded-xl font-cyber text-xs uppercase tracking-wider transition-all flex items-center space-x-2">
            <i data-lucide="plug" class="w-4 h-4"></i>
            <span>🔌 কাস্টম এপিআই ({{ $customGateways->count() }})</span>
        </button>
    </div>

    <!-- 1. TAB: PAYMENT GATEWAYS -->
    <div x-show="currentTab === 'payment'" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($paymentGateways as $gw)
            <div class="admin-glass rounded-3xl p-6 border {{ $gw->is_active ? 'border-cyan-500/40 bg-slate-900/60' : 'border-slate-800 bg-slate-950/60 opacity-80' }} space-y-5 transition-all">
                
                <!-- Gateway Header -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center text-cyan-400 border border-cyan-500/20">
                            <i data-lucide="{{ $gw->icon ?: 'credit-card' }}" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="font-cyber font-bold text-sm text-white">{{ $gw->display_name }}</h3>
                            <span class="text-[10px] font-mono text-slate-400">{{ $gw->gateway_code }}</span>
                        </div>
                    </div>

                    <!-- Single Instant On/Off Toggle -->
                    <form action="{{ route('admin.gateways.toggle', $gw->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1 rounded-full text-xs font-mono font-bold transition-all {{ $gw->is_active ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : 'bg-slate-800 text-slate-500 border border-slate-700' }}" title="Click to Toggle Active/Inactive">
                            {{ $gw->is_active ? '● LIVE ACTIVE' : '○ DISABLED' }}
                        </button>
                    </form>
                </div>

                <!-- Individual Form (Only saves this single gateway!) -->
                <form action="{{ route('admin.gateways.update', $gw->id) }}" method="POST" class="space-y-4 font-mono text-xs">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-slate-300">গেটওয়ের নাম</label>
                            <input type="text" name="display_name" value="{{ $gw->display_name }}" required class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white">
                        </div>

                        <div class="space-y-1">
                            <label class="text-slate-300">এনভায়রনমেন্ট মোড</label>
                            <select name="is_sandbox" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white">
                                <option value="0" {{ !$gw->is_sandbox ? 'selected' : '' }}>🟢 Live Production</option>
                                <option value="1" {{ $gw->is_sandbox ? 'selected' : '' }}>🟡 Sandbox (Test Mode)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Dynamic Credentials Fields -->
                    @if(is_array($gw->credentials))
                        <div class="p-4 rounded-2xl bg-slate-950/80 border border-slate-800 space-y-3">
                            <span class="text-[10px] font-bold text-cyan-400 uppercase tracking-wider block">API CREDENTIALS (গোপন এপিআই তথ্য):</span>
                            @foreach($gw->credentials as $cKey => $cVal)
                                <div class="space-y-1">
                                    <label class="text-slate-400 capitalize">{{ str_replace('_', ' ', $cKey) }}</label>
                                    <input type="{{ str_contains($cKey, 'secret') || str_contains($cKey, 'pass') || str_contains($cKey, 'key') ? 'password' : 'text' }}" 
                                           name="credentials[{{ $cKey }}]" 
                                           value="{{ $cVal }}" 
                                           class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-white font-mono">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="space-y-1">
                        <label class="text-slate-400">নির্দেশনা ও নোটস</label>
                        <input type="text" name="instructions" value="{{ $gw->instructions }}" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-1.5 text-slate-300">
                    </div>

                    <!-- Action Buttons for This Gateway -->
                    <div class="flex items-center justify-between pt-2">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-300 border border-cyan-500/40 font-cyber font-bold text-xs uppercase tracking-wider transition-all">
                            এই গেটওয়ে সেভ করুন 💾
                        </button>

                        <a href="{{ route('admin.gateways.test', $gw->id) }}" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-all">
                            কানেকশন টেস্ট 🔌
                        </a>
                    </div>
                </form>

            </div>
        @endforeach
    </div>

    <!-- 2. TAB: COURIER GATEWAYS -->
    <div x-show="currentTab === 'courier'" class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-cloak>
        @foreach($courierGateways as $gw)
            <div class="admin-glass rounded-3xl p-6 border {{ $gw->is_active ? 'border-cyan-500/40 bg-slate-900/60' : 'border-slate-800 bg-slate-950/60 opacity-80' }} space-y-5 transition-all">
                
                <!-- Gateway Header -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center text-cyan-400 border border-cyan-500/20">
                            <i data-lucide="{{ $gw->icon ?: 'truck' }}" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="font-cyber font-bold text-sm text-white">{{ $gw->display_name }}</h3>
                            <span class="text-[10px] font-mono text-slate-400">{{ $gw->gateway_code }}</span>
                        </div>
                    </div>

                    <form action="{{ route('admin.gateways.toggle', $gw->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1 rounded-full text-xs font-mono font-bold transition-all {{ $gw->is_active ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : 'bg-slate-800 text-slate-500 border border-slate-700' }}">
                            {{ $gw->is_active ? '● LIVE ACTIVE' : '○ DISABLED' }}
                        </button>
                    </form>
                </div>

                <!-- Individual Form -->
                <form action="{{ route('admin.gateways.update', $gw->id) }}" method="POST" class="space-y-4 font-mono text-xs">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-slate-300">কুরিয়ারের নাম</label>
                            <input type="text" name="display_name" value="{{ $gw->display_name }}" required class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white">
                        </div>

                        <div class="space-y-1">
                            <label class="text-slate-300">এনভায়রনমেন্ট মোড</label>
                            <select name="is_sandbox" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white">
                                <option value="0" {{ !$gw->is_sandbox ? 'selected' : '' }}>🟢 Live Production</option>
                                <option value="1" {{ $gw->is_sandbox ? 'selected' : '' }}>🟡 Sandbox (Test Mode)</option>
                            </select>
                        </div>
                    </div>

                    @if(is_array($gw->credentials))
                        <div class="p-4 rounded-2xl bg-slate-950/80 border border-slate-800 space-y-3">
                            <span class="text-[10px] font-bold text-cyan-400 uppercase tracking-wider block">COURIER API KEYS & ENDPOINT:</span>
                            @foreach($gw->credentials as $cKey => $cVal)
                                <div class="space-y-1">
                                    <label class="text-slate-400 capitalize">{{ str_replace('_', ' ', $cKey) }}</label>
                                    <input type="{{ str_contains($cKey, 'secret') || str_contains($cKey, 'key') || str_contains($cKey, 'pass') ? 'password' : 'text' }}" 
                                           name="credentials[{{ $cKey }}]" 
                                           value="{{ $cVal }}" 
                                           class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-white font-mono">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="space-y-1">
                        <label class="text-slate-400">নির্দেশনা ও নোটস</label>
                        <input type="text" name="instructions" value="{{ $gw->instructions }}" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-1.5 text-slate-300">
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-300 border border-cyan-500/40 font-cyber font-bold text-xs uppercase tracking-wider transition-all">
                            এই কুরিয়ার সেভ করুন 💾
                        </button>

                        <a href="{{ route('admin.gateways.test', $gw->id) }}" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-all">
                            এপিআই টেস্ট 🔌
                        </a>
                    </div>
                </form>

            </div>
        @endforeach
    </div>

    <!-- 3. TAB: SMS GATEWAYS -->
    <div x-show="currentTab === 'sms'" class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-cloak>
        @foreach($smsGateways as $gw)
            <div class="admin-glass rounded-3xl p-6 border {{ $gw->is_active ? 'border-cyan-500/40 bg-slate-900/60' : 'border-slate-800 bg-slate-950/60 opacity-80' }} space-y-5 transition-all">
                
                <!-- Gateway Header -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center text-cyan-400 border border-cyan-500/20">
                            <i data-lucide="message-square" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="font-cyber font-bold text-sm text-white">{{ $gw->display_name }}</h3>
                            <span class="text-[10px] font-mono text-slate-400">{{ $gw->gateway_code }}</span>
                        </div>
                    </div>

                    <form action="{{ route('admin.gateways.toggle', $gw->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1 rounded-full text-xs font-mono font-bold transition-all {{ $gw->is_active ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : 'bg-slate-800 text-slate-500 border border-slate-700' }}">
                            {{ $gw->is_active ? '● LIVE ACTIVE' : '○ DISABLED' }}
                        </button>
                    </form>
                </div>

                <!-- Individual Form -->
                <form action="{{ route('admin.gateways.update', $gw->id) }}" method="POST" class="space-y-4 font-mono text-xs">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-slate-300">এসএমএস গেটওয়ের নাম</label>
                            <input type="text" name="display_name" value="{{ $gw->display_name }}" required class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white">
                        </div>

                        <div class="space-y-1">
                            <label class="text-slate-300">এনভায়রনমেন্ট মোড</label>
                            <select name="is_sandbox" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white">
                                <option value="0" {{ !$gw->is_sandbox ? 'selected' : '' }}>🟢 Live Production</option>
                                <option value="1" {{ $gw->is_sandbox ? 'selected' : '' }}>🟡 Sandbox (Test Mode)</option>
                            </select>
                        </div>
                    </div>

                    @if(is_array($gw->credentials))
                        <div class="p-4 rounded-2xl bg-slate-950/80 border border-slate-800 space-y-3">
                            <span class="text-[10px] font-bold text-cyan-400 uppercase tracking-wider block">SMS GATEWAY API TOKEN:</span>
                            @foreach($gw->credentials as $cKey => $cVal)
                                <div class="space-y-1">
                                    <label class="text-slate-400 capitalize">{{ str_replace('_', ' ', $cKey) }}</label>
                                    <input type="{{ str_contains($cKey, 'token') || str_contains($cKey, 'key') || str_contains($cKey, 'pass') ? 'password' : 'text' }}" 
                                           name="credentials[{{ $cKey }}]" 
                                           value="{{ $cVal }}" 
                                           class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-white font-mono">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="space-y-1">
                        <label class="text-slate-400">নির্দেশনা ও নোটস</label>
                        <input type="text" name="instructions" value="{{ $gw->instructions }}" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-1.5 text-slate-300">
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-300 border border-cyan-500/40 font-cyber font-bold text-xs uppercase tracking-wider transition-all">
                            এসএমএস গেটওয়ে সেভ করুন 💾
                        </button>

                        <a href="{{ route('admin.gateways.test', $gw->id) }}" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-all">
                            ব্যালেন্স চেক 💰
                        </a>
                    </div>
                </form>

            </div>
        @endforeach
    </div>

    <!-- 4. TAB: CUSTOM / OTHER GATEWAYS -->
    <div x-show="currentTab === 'other'" class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-cloak>
        @forelse($customGateways as $gw)
            <div class="admin-glass rounded-3xl p-6 border border-cyan-500/30 space-y-5">
                <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                    <div>
                        <h3 class="font-cyber font-bold text-sm text-white">{{ $gw->display_name }}</h3>
                        <span class="text-[10px] font-mono text-slate-400">Custom Gateway</span>
                    </div>

                    <form action="{{ route('admin.gateways.destroy', $gw->id) }}" method="POST" onsubmit="return confirm('এই গেটওয়ে মুছে ফেলতে চান?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-slate-500 hover:text-red-400 p-1">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>

                <form action="{{ route('admin.gateways.update', $gw->id) }}" method="POST" class="space-y-4 font-mono text-xs">
                    @csrf
                    @method('PUT')

                    <div class="space-y-1">
                        <label class="text-slate-300">গেটওয়ের নাম</label>
                        <input type="text" name="display_name" value="{{ $gw->display_name }}" required class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white">
                    </div>

                    @if(is_array($gw->credentials))
                        <div class="p-4 rounded-2xl bg-slate-950/80 border border-slate-800 space-y-3">
                            <span class="text-[10px] font-bold text-cyan-400 uppercase tracking-wider block">CUSTOM API CONFIGURATION:</span>
                            @foreach($gw->credentials as $cKey => $cVal)
                                <div class="space-y-1">
                                    <label class="text-slate-400">{{ $cKey }}</label>
                                    <input type="text" name="credentials[{{ $cKey }}]" value="{{ $cVal }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-white font-mono">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 font-cyber font-bold text-xs">
                        সংরক্ষণ করুন 💾
                    </button>
                </form>
            </div>
        @empty
            <div class="lg:col-span-2 p-12 admin-glass rounded-3xl text-center text-xs text-slate-500 space-y-3">
                <p>কোনো কাস্টম গেটওয়ে যোগ করা নেই।</p>
                <button @click="showAddModal = true" class="px-4 py-2 rounded-xl bg-cyan-500 text-slate-950 font-bold">
                    + নতুন কাস্টম গেটওয়ে তৈরি করুন
                </button>
            </div>
        @endforelse
    </div>

    <!-- Modal: Add New Gateway -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" @click="showAddModal = false"></div>

        <div class="relative w-full max-w-lg bg-slate-900 border border-cyan-500/40 rounded-3xl p-6 sm:p-8 shadow-2xl z-10 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="font-cyber font-bold text-base text-white">নতুন গেটওয়ে বা এপিআই যোগ করুন 🔌</h3>
                <button @click="showAddModal = false" class="text-slate-400 hover:text-white">✕</button>
            </div>

            <form action="{{ route('admin.gateways.store') }}" method="POST" class="space-y-4 font-mono text-xs">
                @csrf

                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-slate-300">গেটওয়ের ধরণ (Type) *</label>
                        <select name="gateway_type" required class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white">
                            <option value="payment">💳 পেমেন্ট গেটওয়ে (Payment)</option>
                            <option value="courier">🚚 কুরিয়ার এপিআই (Courier)</option>
                            <option value="sms">📱 এসএমএস গেটওয়ে (SMS)</option>
                            <option value="other">🔌 অন্যান্য / কাস্টম ওয়েবহুক</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-slate-300">গেটওয়ের নাম (Display Name) *</label>
                        <input type="text" name="display_name" required placeholder="e.g. Upay Merchant API" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white">
                    </div>
                </div>

                <!-- Dynamic Credentials Builder -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-slate-300">এপিআই ক্রেডেনশিয়াল ফিল্ডসমূহ (Key & Value)</label>
                        <button type="button" @click="customFields.push({ key: '', value: '' })" class="text-cyan-400 text-[10px] hover:underline">+ ফিল্ড যোগ করুন</button>
                    </div>

                    <template x-for="(field, index) in customFields" :key="index">
                        <div class="flex items-center space-x-2">
                            <input type="text" name="credentials_keys[]" placeholder="Field Key (e.g. api_key)" x-model="field.key" class="w-1/2 bg-slate-950 border border-slate-700 rounded-xl px-3 py-1.5 text-white">
                            <input type="text" name="credentials_values[]" placeholder="Field Value / Secret" x-model="field.value" class="w-1/2 bg-slate-950 border border-slate-700 rounded-xl px-3 py-1.5 text-white">
                            <button type="button" @click="customFields.splice(index, 1)" class="text-slate-500 hover:text-red-400 p-1" x-show="customFields.length > 1">✕</button>
                        </div>
                    </template>
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300">নির্দেশনা ও নোটস</label>
                    <textarea name="instructions" rows="2" placeholder="এই গেটওয়ে ব্যবহারের নিয়মাবলী..." class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white"></textarea>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider shadow-lg hover:scale-105 transition-all">
                    গেটওয়ে তৈরি ও সক্রিয় করুন 🚀
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
