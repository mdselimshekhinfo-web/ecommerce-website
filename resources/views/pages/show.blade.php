@extends('layouts.app')

@section('title', $page->title . ' // NEXUS DOKAN BD')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12 space-y-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-xs font-mono text-slate-400">
        <a href="{{ route('home') }}" class="hover:text-cyan-400">Home</a>
        <span>/</span>
        <span class="text-white">{{ $page->title }}</span>
    </nav>

    <!-- Content Card -->
    <div class="glass-panel rounded-3xl p-6 sm:p-12 border border-slate-800 space-y-6">
        <h1 class="text-2xl sm:text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-sky-100 to-pink-400">
            {{ $page->title }}
        </h1>

        <div class="prose prose-invert max-w-none text-slate-300 text-sm leading-relaxed space-y-4 font-sans border-t border-slate-800 pt-6">
            {!! $page->content !!}
        </div>
    </div>

</div>
@endsection
