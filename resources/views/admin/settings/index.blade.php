@extends('layouts.admin')

@section('page-title', 'এপিআই ও সিস্টেম কনফিগারেশন (API & System Settings)')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6 font-mono text-xs">
        @csrf

        <!-- 1. Courier Logistics APIs -->
        <div class="admin-glass rounded-3xl p-6 sm:p-8 border border-slate-800 space-y-4">
            <div class="flex items-center space-x-2 pb-3 border-b border-slate-800">
                <i data-lucide="truck" class="w-4 h-4 text-cyan-400"></i>
                <h3 class="font-cyber font-bold text-sm text-white uppercase tracking-wider">Courier API Credentials (Steadfast / Pathao)</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-slate-300">Steadfast Courier API Key *</label>
                    <input type="text" name="steadfast_api_key" value="{{ $settings['steadfast_api_key'] ?? '' }}" placeholder="stf_live_api_..." 
                           class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white font-bold">
                </div>
                <div class="space-y-1">
                    <label class="text-slate-300">Steadfast Secret Key *</label>
                    <input type="password" name="steadfast_secret_key" value="{{ $settings['steadfast_secret_key'] ?? '' }}" placeholder="••••••••••••" 
                           class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white font-bold">
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300">Pathao Courier Client ID</label>
                    <input type="text" name="pathao_client_id" value="{{ $settings['pathao_client_id'] ?? '' }}" placeholder="Client ID" 
                           class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white">
                </div>
                <div class="space-y-1">
                    <label class="text-slate-300">Pathao Client Secret</label>
                    <input type="password" name="pathao_secret_key" value="{{ $settings['pathao_secret_key'] ?? '' }}" placeholder="••••••••••••" 
                           class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white">
                </div>
            </div>
        </div>

        <!-- 2. SMS Gateway APIs -->
        <div class="admin-glass rounded-3xl p-6 sm:p-8 border border-slate-800 space-y-4">
            <div class="flex items-center space-x-2 pb-3 border-b border-slate-800">
                <i data-lucide="message-square" class="w-4 h-4 text-emerald-400"></i>
                <h3 class="font-cyber font-bold text-sm text-white uppercase tracking-wider">SMS Gateway Configuration (GreenWeb / BulkSMS BD)</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-slate-300">SMS Gateway API Token / Key *</label>
                    <input type="text" name="sms_api_key" value="{{ $settings['sms_api_key'] ?? '' }}" placeholder="gw_live_sms_..." 
                           class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white font-bold">
                </div>
                <div class="space-y-1">
                    <label class="text-slate-300">Approved Sender ID (মাস্কিং নাম) *</label>
                    <input type="text" name="sms_sender_id" value="{{ $settings['sms_sender_id'] ?? 'NEXUS DOKAN' }}" placeholder="NEXUS DOKAN" 
                           class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white font-bold">
                </div>
            </div>
        </div>

        <!-- 3. Mail & General Configuration -->
        <div class="admin-glass rounded-3xl p-6 sm:p-8 border border-slate-800 space-y-4">
            <div class="flex items-center space-x-2 pb-3 border-b border-slate-800">
                <i data-lucide="mail" class="w-4 h-4 text-pink-400"></i>
                <h3 class="font-cyber font-bold text-sm text-white uppercase tracking-wider">Email SMTP & General Store Config</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-slate-300">SMTP Host</label>
                    <input type="text" name="smtp_host" value="{{ $settings['smtp_host'] ?? 'smtp.mailtrap.io' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                </div>
                <div class="space-y-1">
                    <label class="text-slate-300">SMTP Port</label>
                    <input type="text" name="smtp_port" value="{{ $settings['smtp_port'] ?? '587' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                </div>
                <div class="space-y-1">
                    <label class="text-slate-300">Currency Symbol</label>
                    <input type="text" name="store_currency_symbol" value="{{ $settings['store_currency_symbol'] ?? '৳' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white font-bold">
                </div>
                <div class="space-y-1">
                    <label class="text-slate-300">Order Invoice Prefix</label>
                    <input type="text" name="order_prefix" value="{{ $settings['order_prefix'] ?? 'NX' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white font-bold">
                </div>
            </div>
        </div>

        <button type="submit" class="w-full py-4 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider shadow-lg hover:scale-105 transition-all flex items-center justify-center space-x-2">
            <i data-lucide="save" class="w-4 h-4"></i>
            <span>কনফিগারেশন সেটিংস সেভ করুন ⚙️</span>
        </button>

    </form>

</div>
@endsection
