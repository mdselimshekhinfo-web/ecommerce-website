@extends('layouts.app')

@section('title', 'Reset Password // NEXUS DOKAN BD')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">

    <div class="glass-card rounded-3xl p-8 space-y-6 border border-cyan-500/30 shadow-2xl">
        
        <!-- Header -->
        <div class="text-center space-y-2">
            <div class="w-12 h-12 mx-auto rounded-2xl bg-pink-500/20 border border-pink-400/40 flex items-center justify-center">
                <i data-lucide="lock-keyhole" class="w-6 h-6 text-pink-400"></i>
            </div>
            <h1 class="font-cyber font-black text-2xl text-white tracking-wide">
                RESET PASSWORD
            </h1>
            <p class="text-xs text-slate-400">আপনার ইমেইল দিন — আমরা রিসেট লিঙ্ক পাঠাবো।</p>
        </div>

        @if(session('status'))
            <div class="p-4 rounded-xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-300 text-sm flex items-center space-x-2">
                <i data-lucide="check-circle-2" class="w-5 h-5 shrink-0"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-xl bg-red-950/80 border border-red-500/40 text-red-300 text-xs space-y-1">
                @foreach($errors->all() as $err)
                    <p>{{ $err }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="space-y-1.5">
                <label class="font-mono text-xs text-slate-300">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="your.name@example.com" 
                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
            </div>

            <button type="submit" class="w-full py-3.5 rounded-xl cyber-btn font-cyber font-bold text-xs uppercase tracking-wider shadow-neon-cyan flex items-center justify-center space-x-2">
                <i data-lucide="mail" class="w-4 h-4"></i>
                <span>Send Reset Link</span>
            </button>
        </form>

        <div class="text-center pt-2 text-xs text-slate-400">
            <span>Remembered your password?</span>
            <a href="{{ route('login') }}" class="text-cyan-400 font-bold hover:underline ml-1">Back to Login ➔</a>
        </div>

    </div>

</div>
@endsection
