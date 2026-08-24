<!DOCTYPE html>
<html lang="bn" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $landingPage->title }} // Special Deal BD</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;800;900&family=Rajdhani:wght@500;600;700&family=Hind+Siliguri:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Hind Siliguri', 'Rajdhani', sans-serif; background-color: #07080e; color: #f1f5f9; }
        .font-cyber { font-family: 'Orbitron', sans-serif; }
        .glass-card { background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .shadow-neon-cyan { box-shadow: 0 0 25px rgba(0, 242, 254, 0.35); }
        .shadow-neon-pink { box-shadow: 0 0 25px rgba(255, 0, 127, 0.35); }
    </style>

    {!! \App\Helpers\PixelHelper::renderHeaderTags() !!}
</head>
<body class="min-h-screen flex flex-col antialiased selection:bg-cyan-500 selection:text-black">
    {!! \App\Helpers\PixelHelper::renderBodyTags() !!}

    <!-- 1. Top Urgency Marquee & Countdown -->
    <div class="sticky top-0 z-50 bg-gradient-to-r from-pink-600 via-purple-600 to-cyan-500 text-slate-950 px-4 py-2 text-center text-xs sm:text-sm font-bold flex items-center justify-center space-x-2 shadow-lg">
        <span>⚡ ধামাকা অফার! আর মাত্র সীমিত সংখ্যক স্টক বাকি আছে।</span>
        <span class="bg-black/80 text-white px-2 py-0.5 rounded font-mono text-xs">অফার শেষ হতে বাকি: <span id="countdown">04:18:22</span></span>
    </div>

    <!-- 2. Main Content Container -->
    <main class="flex-1 max-w-4xl mx-auto px-4 py-8 sm:py-12 space-y-12">

        <!-- Hero Headline & Offer Pricing -->
        <div class="text-center space-y-4">
            <span class="px-4 py-1 rounded-full bg-cyan-500/10 text-cyan-400 border border-cyan-400/40 text-xs font-mono font-bold tracking-widest uppercase inline-block">
                🔥 100% GENUINE PRODUCT BD
            </span>

            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-sky-100 to-pink-400 leading-tight">
                {{ $landingPage->headline ?: $landingPage->title }}
            </h1>

            <p class="text-sm sm:text-base text-slate-300 max-w-2xl mx-auto leading-relaxed">
                {{ $landingPage->subheadline }}
            </p>

            <!-- Pricing Box -->
            <div class="flex items-center justify-center space-x-4 pt-2">
                <span class="text-3xl sm:text-4xl font-black text-emerald-400 font-mono">
                    {{ \App\Helpers\BanglaHelper::formatTaka($landingPage->offer_price) }}
                </span>
                @if($landingPage->regular_price)
                    <span class="text-lg sm:text-xl text-slate-500 line-through font-mono">
                        {{ \App\Helpers\BanglaHelper::formatTaka($landingPage->regular_price) }}
                    </span>
                    <span class="px-2.5 py-1 rounded-lg bg-pink-500/20 text-pink-400 border border-pink-500/40 text-xs font-bold font-mono">
                        {{ round((($landingPage->regular_price - $landingPage->offer_price) / $landingPage->regular_price) * 100) }}% ছাড়
                    </span>
                @endif
            </div>

            <!-- Fast Scroll Button -->
            <div class="pt-2">
                <a href="#order-form" class="inline-flex items-center space-x-2 px-8 py-4 rounded-2xl bg-gradient-to-r from-pink-500 via-purple-600 to-cyan-400 text-white font-cyber font-bold text-sm uppercase tracking-wider shadow-neon-pink hover:scale-105 transition-all">
                    <span>অর্ডার করতে এখানে ক্লিক করুন (ক্যাশ অন ডেলিভারি) 🛍️</span>
                </a>
            </div>
        </div>

        <!-- Banner / Video Showcase -->
        <div class="glass-card rounded-3xl overflow-hidden shadow-neon-cyan border border-cyan-500/30">
            @if($landingPage->video_url)
                <div class="aspect-video w-full">
                    <iframe src="{{ $landingPage->video_url }}" class="w-full h-full" allowfullscreen></iframe>
                </div>
            @elseif($landingPage->banner_image)
                <img src="{{ $landingPage->banner_image }}" alt="{{ $landingPage->title }}" class="w-full h-auto object-cover max-h-[480px]">
            @endif
        </div>

        <!-- Key Feature Highlights -->
        @if(!empty($landingPage->features_list))
            <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6 border border-slate-800">
                <h3 class="text-xl font-bold text-center text-white flex items-center justify-center space-x-2">
                    <i data-lucide="sparkles" class="w-5 h-5 text-cyan-400"></i>
                    <span>কেন এই প্রোডাক্টটি আপনার কেনা উচিত?</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($landingPage->features_list as $feat)
                        <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800 flex items-start space-x-3">
                            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5"></i>
                            <span class="text-sm text-slate-200 leading-snug">{{ $feat }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Customer Reviews & Social Proof -->
        @if(count($reviews) > 0)
            <div class="space-y-6">
                <h3 class="text-xl font-bold text-center text-white">গ্রাহকদের বিশ্বস্ত রিভিউ ও মতামত ⭐</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($reviews as $rev)
                        <div class="glass-card rounded-2xl p-5 border border-slate-800 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-white text-sm">{{ $rev->reviewer_name }}</span>
                                <div class="flex text-amber-400 text-xs">
                                    @for($i = 0; $i < $rev->rating; $i++) ★ @endfor
                                </div>
                            </div>
                            <p class="text-xs text-slate-300 leading-relaxed">{{ $rev->comment }}</p>
                            <span class="text-[10px] text-emerald-400 font-mono flex items-center space-x-1">
                                <i data-lucide="shield-check" class="w-3 h-3"></i>
                                <span>Verified Buyer (বাংলাদেশ)</span>
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 3. DIRECT 1-PAGE CHECKOUT ORDER FORM (Cash on Delivery) -->
        <div id="order-form" class="glass-card rounded-3xl p-6 sm:p-10 border-2 border-cyan-400 shadow-neon-cyan space-y-6" x-data="landingOrderForm({{ $landingPage->offer_price }})">
            
            <div class="text-center space-y-1 pb-4 border-b border-slate-800">
                <span class="text-xs font-mono font-bold text-pink-400 uppercase tracking-widest">NO RISK • CASH ON DELIVERY</span>
                <h2 class="text-2xl sm:text-3xl font-black text-white">অর্ডার করতে নিচের ফর্মটি পূরণ করুন</h2>
                <p class="text-xs text-slate-400">পণ্য হাতে পেয়ে চেক করে ডেলিভারিম্যানকে মূল্য পরিশোধ করবেন।</p>
            </div>

            <form action="{{ route('landing.order', $landingPage->slug) }}" method="POST" class="space-y-5">
                @csrf

                <!-- Quantity & Variant Selection -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-2xl bg-slate-900/80 border border-slate-800">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-300">পরিমাণ নির্বাচন করুন (Quantity)</label>
                        <select name="quantity" x-model.number="qty" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white font-bold focus:outline-none focus:border-cyan-400">
                            <option value="1">১ পিস - ৳{{ number_format($landingPage->offer_price, 0) }}</option>
                            <option value="2">২ পিস - ৳{{ number_format($landingPage->offer_price * 2, 0) }} (জনপ্রিয়)</option>
                            <option value="3">৩ পিস - ৳{{ number_format($landingPage->offer_price * 3, 0) }}</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-300">কালার / ভ্যারিয়েন্ট</label>
                        <select name="variant" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none">
                            <option value="Cyber Dark Edition">Cyber Dark Edition (নিয়ন ব্ল্যাক)</option>
                            <option value="Titanium Silver">Titanium Silver (সিলভার)</option>
                        </select>
                    </div>
                </div>

                <!-- Customer Details -->
                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-300">আপনার নাম লিখুন *</label>
                        <input type="text" name="customer_name" required placeholder="আপনার পুরো নাম" 
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-cyan-400 text-sm">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-300">মোবাইল নম্বর (১১ ডিজিট) *</label>
                        <input type="text" name="customer_phone" required placeholder="017XXXXXXXX" 
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 text-white font-mono text-sm focus:outline-none focus:border-cyan-400">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-300">জেলা নির্বাচন করুন *</label>
                        <select name="delivery_district" x-model="district" required class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-cyan-400">
                            <option value="Dhaka">ঢাকা (ঢাকার ভেতরে ডেলিভারি ৳৬০)</option>
                            <option value="Chattogram">চট্টগ্রাম (ঢাকার বাইরে ডেলিভারি ৳১২০)</option>
                            <option value="Gazipur">গাজীপুর</option>
                            <option value="Narayanganj">নারায়ণগঞ্জ</option>
                            <option value="Sylhet">সিলেট</option>
                            <option value="Rajshahi">রাজশাহী</option>
                            <option value="Khulna">খুলনা</option>
                            <option value="Barishal">বরিশাল</option>
                            <option value="Rangpur">রংপুর</option>
                            <option value="Mymensingh">ময়মনসিংহ</option>
                            <option value="Cumilla">কুমিল্লা</option>
                            <option value="Bogura">বগুড়া</option>
                            <option value="Other District">অন্যান্য ৬৪ জেলা</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-300">সম্পূর্ণ ডেলিভারি ঠিকানা (বাসা/রোড/থানা) *</label>
                        <textarea name="delivery_address" rows="2" required placeholder="যেমন: বাসা #১২, রোড #৪, সেক্টর #৭, উত্তরা, ঢাকা" 
                                  class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-cyan-400"></textarea>
                    </div>
                </div>

                <!-- Order Summary Breakdown -->
                <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 space-y-2 text-xs font-mono">
                    <div class="flex justify-between text-slate-400">
                        <span>পণ্যের মোট দাম:</span>
                        <span class="text-white font-bold" x-text="'৳' + (unitPrice * qty).toLocaleString()"></span>
                    </div>
                    <div class="flex justify-between text-slate-400">
                        <span>হোম ডেলিভারি চার্জ:</span>
                        <span class="text-cyan-300 font-bold" x-text="'৳' + getShipping().toLocaleString()"></span>
                    </div>
                    <div class="flex justify-between text-sm font-bold text-white pt-2 border-t border-slate-800">
                        <span>সর্বমোট প্রদেয় বিল (COD):</span>
                        <span class="text-emerald-400 text-lg font-black" x-text="'৳' + getTotal().toLocaleString()"></span>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-4 rounded-2xl bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 text-slate-950 font-cyber font-black text-sm uppercase tracking-wider shadow-neon-green hover:scale-[1.02] active:scale-95 transition-all">
                    অর্ডার কনফার্ম করুন (ক্যাশ অন ডেলিভারি) 🛍️
                </button>

                <div class="flex items-center justify-center space-x-4 text-[11px] text-slate-400 text-center pt-2">
                    <span>🔒 ১০০% নিরাপদ চেকআউট</span>
                    <span>•</span>
                    <span>📦 ৭ দিনের ফ্রি রিটার্ন</span>
                    <span>•</span>
                    <span>⚡ ২৪ ঘণ্টায় ফাস্ট ডেলিভারি</span>
                </div>

            </form>

        </div>

    </main>

    <!-- Footer -->
    <footer class="mt-12 py-6 border-t border-slate-800 text-center text-xs text-slate-500 space-y-1">
        <p>© 2026 NEXUS DOKAN BD • Official Cyberpunk eCommerce Bangladesh</p>
        <p>গুলশান-২, ঢাকা-১২১২ • 24/7 কাস্টমার সাপোর্ট: 01711-000111</p>
    </footer>

    <script>
        lucide.createIcons();

        function landingOrderForm(unitPrice) {
            return {
                unitPrice: unitPrice,
                qty: 1,
                district: 'Dhaka',

                getShipping() {
                    return (this.district.toLowerCase() === 'dhaka') ? 60 : 120;
                },

                getTotal() {
                    return (this.unitPrice * this.qty) + this.getShipping();
                }
            }
        }

        // Live Fake Countdown Timer
        let time = 4 * 3600 + 18 * 60 + 22;
        setInterval(() => {
            if (time > 0) {
                time--;
                let h = Math.floor(time / 3600);
                let m = Math.floor((time % 3600) / 60);
                let s = time % 60;
                document.getElementById('countdown').innerText = 
                    (h < 10 ? '0' + h : h) + ':' + (m < 10 ? '0' + m : m) + ':' + (s < 10 ? '0' + s : s);
            }
        }, 1000);
    </script>
</body>
</html>
