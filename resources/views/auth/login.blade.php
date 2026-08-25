@extends('layouts.app')

@section('title', 'Cyber Access // Login - NEXUS DOKAN')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">

    <div class="glass-card rounded-3xl p-8 space-y-6 border border-cyan-500/30 shadow-2xl">
        
        <!-- Header -->
        <div class="text-center space-y-2">
            <div class="w-12 h-12 mx-auto rounded-2xl bg-cyan-500/20 border border-cyan-400/40 flex items-center justify-center shadow-neon-cyan">
                <i data-lucide="key-round" class="w-6 h-6 text-cyan-400"></i>
            </div>
            <h1 class="font-cyber font-black text-2xl text-white tracking-wide">
                CYBER ACCESS
            </h1>
            <p class="text-xs text-slate-400">Sign in to manage your orders, wishlist, and vouchers.</p>
        </div>

        <!-- 1-Click Demo Login Shortcuts -->
        <div class="p-3.5 rounded-2xl bg-slate-900/90 border border-slate-800 space-y-2 text-center">
            <span class="text-[10px] font-mono text-cyan-400 uppercase font-bold">⚡ ONE-CLICK INSTANT DEMO LOGIN</span>
            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('quick.login', 'customer') }}" class="py-2 px-2.5 rounded-xl bg-cyan-500/10 hover:bg-cyan-500/20 border border-cyan-500/30 text-cyan-300 font-mono text-[11px] font-bold text-center transition-all">
                    Customer Demo
                </a>
                <a href="{{ route('quick.login', 'admin') }}" class="py-2 px-2.5 rounded-xl bg-purple-500/10 hover:bg-purple-500/20 border border-purple-500/30 text-purple-300 font-mono text-[11px] font-bold text-center transition-all">
                    Admin Demo
                </a>
            </div>
        </div>

        <!-- Standard Login Form -->
        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="space-y-1.5">
                <label class="font-mono text-xs text-slate-300">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="your.name@example.com" 
                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
            </div>

            <div class="space-y-1.5">
                <label class="font-mono text-xs text-slate-300">Password</label>
                <input type="password" name="password" required placeholder="••••••••" 
                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center space-x-2 text-slate-400 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded text-cyan-500 focus:ring-0">
                    <span>Remember me</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-cyan-400 hover:text-cyan-300 font-semibold hover:underline">
                    Forgot Password?
                </a>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-xl cyber-btn font-cyber font-bold text-xs uppercase tracking-wider shadow-neon-cyan flex items-center justify-center space-x-2">
                <i data-lucide="log-in" class="w-4 h-4"></i>
                <span>Authorize & Login</span>
            </button>
        </form>

        <!-- Register Switch -->
        <div class="text-center pt-2 text-xs text-slate-400">
            <span>Don't have an account?</span>
            <a href="{{ route('register') }}" class="text-cyan-400 font-bold hover:underline ml-1">Create Account ➔</a>
        </div>

    </div>

</div>
@endsection
