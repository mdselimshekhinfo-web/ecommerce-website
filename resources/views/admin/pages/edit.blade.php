@extends('layouts.admin')

@section('page-title', 'পলিসি পেজ এডিট: ' . $page->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    <div class="admin-glass rounded-3xl p-6 sm:p-8 border border-slate-800 space-y-6">
        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
            <h3 class="font-cyber font-bold text-base text-white">পলিসি পেজ সম্পাদনা</h3>
            <a href="{{ route('admin.pages.index') }}" class="text-xs text-slate-400 font-mono hover:text-white">← ফিরে যান</a>
        </div>

        <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" class="space-y-6 font-mono text-xs">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-slate-300">পেজের শিরোনাম (Title) *</label>
                    <input type="text" name="title" value="{{ $page->title }}" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white">
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300">URL স্লাগ *</label>
                    <input type="text" name="slug" value="{{ $page->slug }}" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white font-bold">
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-slate-300">পেজের মূল কন্টেন্ট (HTML/Text) *</label>
                <textarea name="content" rows="12" required class="w-full bg-slate-900 border border-slate-700 rounded-xl p-4 text-white font-mono text-xs">{{ $page->content }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-slate-300">স্ট্যাটাস</label>
                    <select name="status" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white">
                        <option value="published" {{ $page->status === 'published' ? 'selected' : '' }}>Published (সরাসরি লাইভ)</option>
                        <option value="draft" {{ $page->status === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>

                <div class="space-y-1 pt-6">
                    <label class="inline-flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="is_footer_link" value="1" {{ $page->is_footer_link ? 'checked' : '' }} class="w-4 h-4 rounded text-cyan-400 bg-slate-900 border-slate-700">
                        <span class="text-slate-300">ওয়েবসাইটের ফুটারে লিংক প্রদর্শন করুন</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="w-full py-4 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider shadow-lg hover:scale-105 transition-all">
                পরিবর্তন সংরক্ষণ করুন 💾
            </button>
        </form>
    </div>

</div>
@endsection
