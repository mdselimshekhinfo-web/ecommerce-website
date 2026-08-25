@extends('layouts.admin')

@section('page-title', 'স্টোর সেটিংস, সোশ্যাল কানেকশন ও এপিআই')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-8 font-mono text-xs">
        @csrf

        <!-- ================================================================= -->
        <!-- 1. Facebook Page, Messenger & Social Channels Hub -->
        <!-- ================================================================= -->
        <div class="admin-glass rounded-3xl p-6 sm:p-8 border border-blue-500/40 space-y-6 relative overflow-hidden">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <div class="flex items-center space-x-3">
                    <div class="w-11 h-11 rounded-2xl bg-blue-500/20 text-blue-400 border border-blue-500/40 flex items-center justify-center">
                        <i data-lucide="facebook" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="font-cyber font-bold text-sm sm:text-base text-white">🌐 ফেসবুক পেজ, মেসেঞ্জার ও সোশ্যাল চ্যানেল কানেকশন</h3>
                        <p class="text-xs text-slate-400 font-sans mt-0.5">আপনার ফেসবুক পেজ এবং সোশ্যাল মিডিয়া প্রোফাইল যুক্ত করুন।</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-blue-500/10 text-blue-400 text-[10px] font-bold border border-blue-500/30">Active Channels</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                
                <!-- Facebook Page Username / URL -->
                <div class="space-y-1.5 bg-slate-900/60 p-4 rounded-2xl border border-slate-800">
                    <label class="text-slate-200 font-bold flex items-center gap-1.5">
                        <i data-lucide="facebook" class="w-4 h-4 text-blue-400"></i>
                        <span>ফেসবুক পেজ ইউজারনেম বা লিংক *</span>
                    </label>
                    <input type="text" name="facebook_page_username" value="{{ $settings['facebook_page_username'] ?? 'nexusdokan' }}" placeholder="যেমন: nexusdokan বা facebook.com/nexusdokan" 
                           class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white font-bold focus:border-blue-400 focus:outline-none font-mono">
                    <p class="text-[10px] text-slate-400 font-sans">
                        💡 <b>কীভাবে পাবেন:</b> আপনার ফেসবুক পেজের লিংক কপি করে এখানে পেস্ট করুন।
                    </p>
                </div>

                <!-- Messenger Direct Chat -->
                <div class="space-y-1.5 bg-slate-900/60 p-4 rounded-2xl border border-slate-800">
                    <label class="text-slate-200 font-bold flex items-center gap-1.5">
                        <i data-lucide="message-square" class="w-4 h-4 text-blue-400"></i>
                        <span>মেসেঞ্জার চ্যাট লিংক (m.me)</span>
                    </label>
                    <input type="text" name="facebook_messenger_url" value="{{ $settings['facebook_messenger_url'] ?? 'https://m.me/nexusdokan' }}" placeholder="https://m.me/nexusdokan" 
                           class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-blue-300 font-bold focus:border-blue-400 focus:outline-none font-mono">
                    <p class="text-[10px] text-slate-400 font-sans">
                        কাস্টমার ক্লিক করলেই সরাসরি ফেসবুক মেসেঞ্জারে চ্যাট শুরু হবে।
                    </p>
                </div>

                <!-- Instagram URL -->
                <div class="space-y-1.5 bg-slate-900/60 p-4 rounded-2xl border border-slate-800">
                    <label class="text-slate-200 font-bold flex items-center gap-1.5">
                        <i data-lucide="instagram" class="w-4 h-4 text-pink-400"></i>
                        <span>Instagram প্রোফাইল লিংক</span>
                    </label>
                    <input type="text" name="instagram_url" value="{{ $settings['instagram_url'] ?? 'https://instagram.com/nexusdokan' }}" placeholder="https://instagram.com/yourbrand" 
                           class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:border-pink-400 focus:outline-none font-mono">
                </div>

                <!-- TikTok URL -->
                <div class="space-y-1.5 bg-slate-900/60 p-4 rounded-2xl border border-slate-800">
                    <label class="text-slate-200 font-bold flex items-center gap-1.5">
                        <i data-lucide="video" class="w-4 h-4 text-cyan-400"></i>
                        <span>TikTok প্রোফাইল লিংক</span>
                    </label>
                    <input type="text" name="tiktok_url" value="{{ $settings['tiktok_url'] ?? 'https://tiktok.com/@nexusdokan' }}" placeholder="https://tiktok.com/@yourbrand" 
                           class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:border-cyan-400 focus:outline-none font-mono">
                </div>

                <!-- YouTube URL -->
                <div class="space-y-1.5 bg-slate-900/60 p-4 rounded-2xl border border-slate-800">
                    <label class="text-slate-200 font-bold flex items-center gap-1.5">
                        <i data-lucide="youtube" class="w-4 h-4 text-red-400"></i>
                        <span>YouTube চ্যানেল লিংক</span>
                    </label>
                    <input type="text" name="youtube_url" value="{{ $settings['youtube_url'] ?? 'https://youtube.com/@nexusdokan' }}" placeholder="https://youtube.com/@yourbrand" 
                           class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:border-red-400 focus:outline-none font-mono">
                </div>

                <!-- WhatsApp Hotline -->
                <div class="space-y-1.5 bg-slate-900/60 p-4 rounded-2xl border border-slate-800">
                    <label class="text-slate-200 font-bold flex items-center gap-1.5">
                        <i data-lucide="message-circle" class="w-4 h-4 text-emerald-400"></i>
                        <span>WhatsApp চ্যাট নম্বর</span>
                    </label>
                    <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '+8801947521688' }}" placeholder="+8801947521688" 
                           class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-emerald-300 font-bold focus:border-emerald-400 focus:outline-none font-mono">
                </div>

            </div>
        </div>

        <!-- ================================================================= -->
        <!-- 2. Facebook Pixel & Analytics Tracking Hub -->
        <!-- ================================================================= -->
        <div class="admin-glass rounded-3xl p-6 sm:p-8 border border-purple-500/40 space-y-6">
            <div class="flex items-center space-x-3 pb-4 border-b border-slate-800">
                <div class="w-10 h-10 rounded-2xl bg-purple-500/20 text-purple-400 border border-purple-500/40 flex items-center justify-center">
                    <i data-lucide="activity" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-cyber font-bold text-sm sm:text-base text-white">📊 ফেসবুক পিক্সেল ও অ্যানালিটিক্স ট্র্যাকিং</h3>
                    <p class="text-xs text-slate-400 font-sans mt-0.5">বিজ্ঞাপনের সেলস ও ভিজিটর ট্র্যাক করার জন্য পিক্সেল কোড যুক্ত করুন।</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                
                <div class="space-y-1.5 bg-slate-900/60 p-4 rounded-2xl border border-slate-800">
                    <label class="text-slate-200 font-bold">Meta (Facebook) Pixel ID</label>
                    <input type="text" name="fb_pixel_id" value="{{ $settings['fb_pixel_id'] ?? '' }}" placeholder="যেমন: 1849204829104" 
                           class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white font-bold font-mono focus:border-blue-400 focus:outline-none">
                    <p class="text-[10px] text-slate-500 font-sans">Ads Manager ➔ Events Manager থেকে পাবেন</p>
                </div>

                <div class="space-y-1.5 bg-slate-900/60 p-4 rounded-2xl border border-slate-800">
                    <label class="text-slate-200 font-bold">Google Analytics (GA4) ID</label>
                    <input type="text" name="ga_measurement_id" value="{{ $settings['ga_measurement_id'] ?? '' }}" placeholder="G-XXXXXXXXXX" 
                           class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white font-bold font-mono focus:border-amber-400 focus:outline-none">
                    <p class="text-[10px] text-slate-500 font-sans">Google Analytics Measurement ID</p>
                </div>

                <div class="space-y-1.5 bg-slate-900/60 p-4 rounded-2xl border border-slate-800">
                    <label class="text-slate-200 font-bold">TikTok Pixel ID</label>
                    <input type="text" name="tiktok_pixel_id" value="{{ $settings['tiktok_pixel_id'] ?? '' }}" placeholder="CXXXXXXXXXXXXXXXXX" 
                           class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white font-bold font-mono focus:border-cyan-400 focus:outline-none">
                    <p class="text-[10px] text-slate-500 font-sans">TikTok Ads Manager Pixel</p>
                </div>

            </div>
        </div>

        <!-- ================================================================= -->
        <!-- 3. Courier Logistics APIs (Steadfast & Pathao) -->
        <!-- ================================================================= -->
        <div class="admin-glass rounded-3xl p-6 sm:p-8 border border-slate-800 space-y-4">
            <div class="flex items-center space-x-2 pb-3 border-b border-slate-800">
                <i data-lucide="truck" class="w-4 h-4 text-cyan-400"></i>
                <h3 class="font-cyber font-bold text-sm text-white uppercase tracking-wider">🚚 Courier API Credentials (Steadfast / Pathao)</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-slate-300 font-bold">Steadfast Courier API Key *</label>
                    <input type="text" name="steadfast_api_key" value="{{ $settings['steadfast_api_key'] ?? '' }}" placeholder="stf_live_api_..." 
                           class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white font-bold">
                </div>
                <div class="space-y-1">
                    <label class="text-slate-300 font-bold">Steadfast Secret Key *</label>
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

        <!-- ================================================================= -->
        <!-- 4. General Store Contact & Email SMTP -->
        <!-- ================================================================= -->
        <div class="admin-glass rounded-3xl p-6 sm:p-8 border border-slate-800 space-y-4">
            <div class="flex items-center space-x-2 pb-3 border-b border-slate-800">
                <i data-lucide="store" class="w-4 h-4 text-pink-400"></i>
                <h3 class="font-cyber font-bold text-sm text-white uppercase tracking-wider">🏪 সাধারণ স্টোর ও কন্টাক্ট ইনফো</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1">
                    <label class="text-slate-300">হটলাইন ফোন নম্বর</label>
                    <input type="text" name="hotline_phone" value="{{ $settings['hotline_phone'] ?? '+8809678831374' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white font-mono">
                </div>
                <div class="space-y-1">
                    <label class="text-slate-300">সাপোর্ট ইমেইল</label>
                    <input type="email" name="support_email" value="{{ $settings['support_email'] ?? 'support@nexusdokan.bd' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white font-mono">
                </div>
                <div class="space-y-1">
                    <label class="text-slate-300">কারেন্সি সিম্বল</label>
                    <input type="text" name="store_currency_symbol" value="{{ $settings['store_currency_symbol'] ?? '৳' }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white font-bold font-mono">
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="w-full py-4 rounded-2xl bg-gradient-to-r from-blue-500 via-indigo-600 to-cyan-400 hover:from-blue-400 hover:to-cyan-300 text-slate-950 font-cyber font-bold text-sm uppercase tracking-wider shadow-2xl transition-all flex items-center justify-center space-x-2">
                <i data-lucide="save" class="w-5 h-5"></i>
                <span>সব সেটিংস ও সোশ্যাল চ্যানেল সংরক্ষণ করুন ⚙️</span>
            </button>
        </div>

    </form>

</div>
@endsection
