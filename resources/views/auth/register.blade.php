@extends('layouts.app')

@section('title', 'Join Nexus // Register Account - NEXUS DOKAN')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">

    <div class="glass-card rounded-3xl p-8 space-y-6 border border-cyan-500/30 shadow-2xl">
        
        <!-- Header -->
        <div class="text-center space-y-2">
            <div class="w-12 h-12 mx-auto rounded-2xl bg-cyan-500/20 border border-cyan-400/40 flex items-center justify-center shadow-neon-cyan">
                <i data-lucide="user-plus" class="w-6 h-6 text-cyan-400"></i>
            </div>
            <h1 class="font-cyber font-black text-2xl text-white tracking-wide">
                JOIN THE NEXUS
            </h1>
            <p class="text-xs text-slate-400">Create a cyber account to track orders and unlock VIP coupons.</p>
        </div>

        <!-- Register Form -->
        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="space-y-1.5">
                <label class="font-mono text-xs text-slate-300">Full Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Mahfuzur Rahman" 
                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
            </div>

            <div class="space-y-1.5">
                <label class="font-mono text-xs text-slate-300">Email Address *</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="mahfuz@gmail.com" 
                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
            </div>

            <div class="space-y-1.5">
                <label class="font-mono text-xs text-slate-300">Phone Number (Bangladesh) *</label>
                <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="01812345678" 
                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400 font-mono">
            </div>

            <div class="space-y-1.5">
                <label class="font-mono text-xs text-slate-300">District / জেলা *</label>
                <select name="district" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                    <option value="Dhaka">Dhaka (ঢাকা)</option>
                    <option value="Chattogram">Chattogram (চট্টগ্রাম)</option>
                    <option value="Sylhet">Sylhet (সিলেট)</option>
                    <option value="Rajshahi">Rajshahi (রাজশাহী)</option>
                    <option value="Khulna">Khulna (খুলনা)</option>
                    <option value="Barishal">Barishal (বরিশাল)</option>
                    <option value="Rangpur">Rangpur (রংপুর)</option>
                    <option value="Mymensingh">Mymensingh (ময়মনসিংহ)</option>
                    <option value="Gazipur">Gazipur (গাজীপুর)</option>
                    <option value="Cumilla">Cumilla (কুমিল্লা)</option>
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="font-mono text-xs text-slate-300">Password (Min 6 chars) *</label>
                <input type="password" name="password" required placeholder="••••••••" 
                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
            </div>

            <div class="space-y-1.5">
                <label class="font-mono text-xs text-slate-300">Confirm Password *</label>
                <input type="password" name="password_confirmation" required placeholder="••••••••" 
                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
            </div>

            <button type="submit" class="w-full py-3.5 rounded-xl cyber-btn font-cyber font-bold text-xs uppercase tracking-wider shadow-neon-cyan flex items-center justify-center space-x-2">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
                <span>Create Cyber Account</span>
            </button>
        </form>

        <!-- Login Switch -->
        <div class="text-center pt-2 text-xs text-slate-400">
            <span>Already have an account?</span>
            <a href="{{ route('login') }}" class="text-cyan-400 font-bold hover:underline ml-1">Login here ➔</a>
        </div>

    </div>

</div>
@endsection
