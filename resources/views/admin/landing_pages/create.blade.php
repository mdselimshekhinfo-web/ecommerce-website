@extends('layouts.admin')

@section('page-title', 'নতুন ১-পেজ ল্যান্ডিং পেজ তৈরি (Create Landing Page)')

@section('content')
<div class="max-w-4xl mx-auto space-y-8" x-data="landingBuilder()">

    <div class="admin-glass rounded-3xl p-6 sm:p-8 border border-slate-800 space-y-6">
        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
            <h3 class="font-cyber font-bold text-base text-white">নতুন সেলস ফানেল ল্যান্ডিং পেজ</h3>
            <a href="{{ route('admin.landing-pages.index') }}" class="text-xs text-slate-400 font-mono hover:text-white">← ফিরে যান</a>
        </div>

        <form action="{{ route('admin.landing-pages.store') }}" method="POST" class="space-y-6 font-mono text-xs">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-slate-300">ল্যান্ডিং পেজের শিরোনাম *</label>
                    <input type="text" name="title" required placeholder="e.g. AuraBlade ANC Cyber Earbuds Pro" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white">
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300">URL স্লাগ (e.g. cyber-earbuds) *</label>
                    <input type="text" name="slug" required placeholder="cyber-earbuds" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white font-bold">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1">
                    <label class="text-slate-300">কানেক্টেড প্রোডাক্ট</label>
                    <select name="product_id" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white">
                        <option value="">-- কোনো প্রোডাক্ট নয় --</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} (৳{{ number_format($p->price, 0) }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300">স্পেশাল অফার মূল্য (৳ Offer Price) *</label>
                    <input type="number" step="0.01" name="offer_price" required placeholder="2450" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-emerald-400 font-bold">
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300">রেগুলার মূল্য (৳ Regular Price)</label>
                    <input type="number" step="0.01" name="regular_price" placeholder="3200" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-slate-400">
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-slate-300">আকর্ষক হেডলাইন (Main Headline) *</label>
                <input type="text" name="headline" placeholder="বাংলাদেশে এই প্রথম -45dB অ্যাক্টিভ নয়েজ ক্যান্সেলেশন সহ সাইবার ইয়ারবাডস!" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white font-bold">
            </div>

            <div class="space-y-1">
                <label class="text-slate-300">সাব-হেডলাইন (Sub-Headline)</label>
                <textarea name="subheadline" rows="2" placeholder="হোলোগ্রাফিক নিয়ন এলইডি ডিসপ্লে, ৪০ ঘণ্টার ব্যাটারি ও আল্ট্রা-লো লেটেন্সি গেমিং মোড।" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-slate-300">ব্যানার / প্রোডাক্ট ইমেজ URL</label>
                    <input type="text" name="banner_image" placeholder="https://images.unsplash.com/..." class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                </div>

                <div class="space-y-1">
                    <label class="text-slate-300">ইউটিউব প্রোডাক্ট ভিডিও Embed URL</label>
                    <input type="text" name="video_url" placeholder="https://www.youtube.com/embed/..." class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                </div>
            </div>

            <!-- Features List (Dynamic) -->
            <div class="space-y-3 pt-3 border-t border-slate-800">
                <div class="flex items-center justify-between">
                    <label class="text-cyan-400 font-bold">প্রোডাক্টের বিশেষ ফিচারসমূহ (Key Features / Bullet Points)</label>
                    <button type="button" @click="addFeature()" class="text-xs text-cyan-300 font-bold hover:underline">+ পয়েন্ট যোগ করুন</button>
                </div>

                <template x-for="(feat, index) in features" :key="index">
                    <div class="flex items-center space-x-2">
                        <input type="text" name="features[]" x-model="features[index]" placeholder="e.g. 🎯 -45dB হাইব্রিড অ্যাক্টিভ নয়েজ ক্যান্সেলেশন" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                        <button type="button" @click="removeFeature(index)" class="text-slate-500 hover:text-red-400 p-2">✕</button>
                    </div>
                </template>
            </div>

            <div class="space-y-1">
                <label class="text-slate-300">স্ট্যাটাস</label>
                <select name="status" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white">
                    <option value="active">Active (সরাসরি লাইভ)</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <button type="submit" class="w-full py-4 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider shadow-lg hover:scale-105 transition-all">
                ল্যান্ডিং পেজ পাবলিশ করুন 🚀
            </button>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function landingBuilder() {
        return {
            features: [
                '🎯 -45dB হাইব্রিড অ্যাক্টিভ নয়েজ ক্যান্সেলেশন (ANC)',
                '🔋 ৪০ ঘণ্টার লং-লাস্টিং ব্যাটারি ও টাইপ-সি ফাস্ট চার্জিং',
                '⚡ ৩৮ms আল্ট্রা-লো লেটেন্সি ডেডিকেটেড গেমিং মোড',
                '🇧🇩 সারা বাংলাদেশে ক্যাশ অন ডেলিভারি (পার্সেল দেখে টাকা পরিশোধ)'
            ],
            addFeature() {
                this.features.push('');
            },
            removeFeature(index) {
                if (this.features.length > 1) {
                    this.features.splice(index, 1);
                }
            }
        }
    }
</script>
@endpush
