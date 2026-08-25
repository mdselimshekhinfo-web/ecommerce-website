# 📚 NEXUS DOKAN BD — MASTER PROJECT ARCHITECTURE & HISTORY

> **📌 Instructions for Any AI / Developer Working on this Project:**
> This document is the single source of truth for all architectural decisions, database models, controllers, business rules, design choices, and feature implementations developed in this codebase. 
> Whenever you start working on this project (on this machine or any other machine), **read this file first** to immediately understand everything built from Day 1 to present. Always update this file when you add or modify features.

---

## 🏛️ ১. প্রজেক্ট পরিচিতি ও টেকনোলজি স্ট্যাক (Tech Stack)

- **Framework**: Laravel 11 (PHP 8.2+)
- **Frontend / Styling**: Tailwind CSS (Executive Minimalist Cyber-Dark Theme)
- **Client Reactivity**: Alpine.js v3 + Lucide Icons
- **Database**: SQLite / MySQL (Fully relational with migrations & seeders)
- **Localization**: Pure Dual-Language Engine (`bn` বাংলা & `en` English) via `App\Helpers\LocalizationHelper`
- **Currency & Numeral Formatting**: `App\Helpers\BanglaHelper` (৳ BDT with English/Bengali numerals)
- **Audio & Speech**: Web Speech Synthesis API (TTS) & Web Speech Recognition (STT)

---

## 🎨 ২. ফ্রন্টএন্ড ডিজাইন সিস্টেম ও ইউজার এক্সপেরিয়েন্স (UI/UX)

1. **মিনিমালিস্ট এক্সিকিউটিভ ডার্ক থিম (Minimalist Executive Dark Theme)**:
   - কালার প্যালেট: গভীর শান্ত স্লিম স্লেট ব্যাকগ্রাউন্ড (`#080c14`), গ্লাস কার্ড (`rgba(15, 23, 42, 0.65)`), মাইক্রো-বর্ডার (`border-white/6`), এবং শান্ত সাইবার অ্যাকসেন্ট।
2. **১-ক্লিক কম্প্যাক্ট ডুয়েল ল্যাঙ্গুয়েজ সুইচ (1-Click Dual Language Switcher)**:
   - টপবারে স্লিম পিল `[ ● 🇧🇩 বাংলা ⇄ ]` / `[ ● 🇬🇧 EN ⇄ ]`।
   - ক্লিক করলেই পুরো ফ্রন্টএন্ড ও ব্যাকএন্ডের সকল হেডার, সাবটাইটেল, বাটন, ব্যাজ ও টেবিল ১০০% বিশুদ্ধ বাংলায় বা ১০০% বিশুদ্ধ ইংরেজিতে রূপান্তরিত হয় (জিরো ল্যাঙ্গুয়েজ লিক)।
3. **ম্যাগনেটিক ইমেজ জুম লেন্স (2.3x Magnetic Product Zoom Lens)**:
   - প্রোডাক্ট পেজে ছবির ওপর মাউস হোভার করলেই স্বয়ংক্রিয়ভাবে ২.৩ গুণ জুম হয়ে ক্রিস্টাল-ক্লিয়ার ক্লোজআপ ভিউ দেখায়।
4. **লাকি স্পিন হুইল ও লাইভ সোশ্যাল প্রুফ স্ট্যাকিং**:
   - স্পিন হুইল বাটনটি নিচে বাম কোণায় ভাসমান: `fixed bottom-6 left-6 z-40` `[ 🎁 স্পিন ও ভাউচার জিতুন ]`।
   - লাইভ পারচেজ টোস্ট পিলটি স্পিন বাটনের ঠিক সামান্য উপরে সুবিন্যস্ত: `fixed bottom-[72px] left-6 z-40`।

---

## 🗄️ ৩. ডাটাবেস স্কিমা ও মডেল রেফারেন্স (Database Models & Migrations)

| Migration ফাইল | মূল টেবিলসমূহ | প্রধান কাজ ও কলাম |
| :--- | :--- | :--- |
| `0001_01_01_000000_create_users_table` | `users` | ইউজার, কাস্টমার ও অ্যাডমিন স্টাফ একাউন্ট (`role`: admin, staff, customer) |
| `2026_01_01_000001_create_categories_table` | `categories` | প্রোডাক্ট ক্যাটাগরি (`name`, `name_bn`, `slug`, `status`) |
| `2026_01_01_000002_create_brands_table` | `brands` | ব্র্যান্ড ক্যাটালগ (`name`, `slug`, `logo`) |
| `2026_01_01_000003_create_products_table` | `products` | প্রোডাক্ট ডিটেইলস (`price`, `sale_price`, `stock_quantity`, `specs`, `variants`, `meta_title`, `meta_description`, `meta_keywords`, `seo_score`) |
| `2026_01_01_000004_create_coupons_table` | `coupons` | ভাউচার কোড (`code`, `discount_type`, `discount_amount`, `min_spend`, `expires_at`) |
| `2026_01_01_000005_create_orders_table` | `orders` | কাস্টমার অর্ডার (`order_number`, `customer_name`, `customer_phone`, `shipping_cost`, `total_amount`, `order_status`, `verification_status`, `voice_call_log`, `courier_name`, `tracking_code`, `courier_consignment_id`) |
| `2026_01_01_000006_create_order_items_table` | `order_items` | অর্ডারের আইটেম লিস্ট (`product_id`, `product_name`, `variant`, `price`, `quantity`, `total`) |
| `2026_01_01_000007_create_reviews_table` | `reviews` / `product_reviews` | কাস্টমার স্টার রেটিং ও রিভিউ |
| `2026_01_01_000009_create_lucky_spins_table` | `lucky_spins` | স্পিন ভাউচার লগ ও আইপি হিস্ট্রি |
| `2026_01_01_000010_create_theme_and_sections_tables` | `theme_settings`, `site_sections` | থিম কনফিগারেশন ও সাইট সেকশন |
| `2026_01_01_000011_create_enterprise_ecommerce_tables` | `sms_logs`, `abandoned_carts`, `suppliers`, `purchase_orders` | এসএমএস হিস্ট্রি, কার্ট রিকভারি, সাপ্লায়ার ও পারচেজ অর্ডার |
| `2026_01_01_000012_create_marketing_and_configuration_tables` | `custom_pages`, `landing_pages`, `blacklisted_ips` | কাস্টম পেজ, ল্যান্ডিং পেজ বিল্ডার ও ফ্রড আইপি ব্লকলিস্ট |
| `2026_01_01_000014_create_custom_gateways_table` | `custom_gateways` | বিকাশ/নগদ/উপায় কাস্টম মার্চেন্ট গেটওয়ে ও কিউআর হাব |
| `2026_01_01_000015_create_live_chat_system_tables` | `chat_sessions`, `chat_messages` | লাইভ চ্যাট সেশন, মেসেজ হিস্ট্রি ও এআই অর্ডার পে-লোড |
| `2026_01_01_000016_add_ai_automation_fields` | `orders`, `products` | এসইও ও এআই ভয়েস কল ভেরিফিকেশন কলাম |

---

## 🤖 ৪. এআই অটো-পাইলট ও হাইব্রিড লাইভ সাপোর্ট ডেস্ক (AI & Support Suite)

### ক. এআই অটো-পাইলট সেলস ও অর্ডার ম্যানেজার (`app/Services/AutoPilotSalesService.php`):
- চ্যাটে কাস্টমার কোনো প্রোডাক্টের নাম, ফোন নম্বর (১১ ডিজিট) ও ডেলিভারি ঠিকানা লিখলে:
  - এআই স্বয়ংক্রিয়ভাবে ঢাকার ভেতরে (৳৬০) বা ঢাকার বাইরে (৳১২০) ডেলিভারি চার্জ হিসাব করে।
  - ডাটাবেসে স্বয়ংক্রিয়ভাবে নতুন অর্ডার (`Order::create`) তৈরি করে এবং ইনভেন্টরি থেকে স্টক কমায়।
  - চ্যাটের ভেতরেই কাস্টমারকে ডিজিটাল অর্ডার কনফার্মেশন মেমো/রিসিপ্ট (#AI-XXXXXX) প্রদর্শন করে।
  - অ্যাডমিন প্যানেলে অর্ডারের পাশে `🤖 Booked via AI Auto-Pilot Sales Agent` যুক্ত হয়।

### খ. হাইব্রিড লাইভ সাপোর্ট ডেস্ক (`/admin/live-chat`):
- অ্যাডমিন বা সাপোর্ট এজেন্টরা রিয়েল-টাইমে স্টোরে সক্রিয় সব কাস্টমার ও তাদের কার্ট দেখতে পারেন এবং মেসেজ পাঠাতে পারেন।
- `[ 🚀 AI Auto-Pilot Switch: ON/OFF ]` এবং `[ 🟢 Agents Online/Offline ]` কন্ট্রোল বার।

### গ. এআই অটো-এসইও ও গুগল স্কিমা ইঞ্জিন (`app/Services/AutoSeoService.php`):
- ১-ক্লিকে সব প্রোডাক্টের জন্য Google Meta Title, Description, Keywords ও JSON-LD Product Schema জেনারেট করে।
- স্বয়ংক্রিয় গুগল সাইটম্যাপ: `/sitemap.xml`।

### ঘ. হোয়াটসঅ্যাপ অটো-ভেরিফিকেশন ও কুরিয়ার ডিসপ্যাচ (`app/Services/WhatsAppVerificationService.php`):
- অর্ডারের পর কাস্টমারের হোয়াটসঅ্যাপে স্বয়ংক্রিয় কনফার্মেশন প্রম্পট যায়।
- কাস্টমার "হ্যাঁ" / "1" লিখে রিপ্লাই দিলেই স্বয়ংক্রিয়ভাবে **Steadfast Courier API কল করে ট্র্যাকিং আইডি (#ST-XXXXXX) বের করে অর্ডার Processing করে ফেলে**।

### ঙ. এআই ভয়েস কলিং কনফার্মেশন এজেন্ট (`app/Services/VoiceCallingService.php`):
- বাংলা ভয়েস ডায়লগ স্ক্রিপ্ট জেনারেটর এবং ব্রাউজার স্পিচ সিন্থেসিস দিয়ে স্বয়ংক্রিয় ভয়েস কল।

---

## 🚚 ৫. কুরিয়ার ও পেমেন্ট গেটওয়ে ইন্টিগ্রেশন

- **Steadfast Courier Integration**: ১-ক্লিকে পার্সেল বুকিং, এপিআই কনসাইনমেন্ট আইডি এবং কুরিয়ার লেবেল প্রিন্ট (`/admin/orders/{id}/courier-label`)।
- **Pathao Courier Integration**: ঢাকার ভেতরে ও বাইরে এক্সপ্রেস ডেলিভারি জোন।
- **পেমেন্ট অপশনসমূহ**:
  1. Cash on Delivery (COD)
  2. bKash Direct / Gateway
  3. Nagad & Rocket Direct
  4. Custom Merchant Gateways with QR Code (`/admin/gateways`)

---

## 🧭 ৬. রাউটিং ও ড্যাশবোর্ড মেনু ম্যাপ (Routes Map)

### 🛍️ পাবলিক স্টোরফ্রন্ট রাউটস:
- `/` ➔ `HomeController@index`
- `/shop` ➔ `ProductController@index`
- `/product/{slug}` ➔ `ProductController@show`
- `/cart` ➔ `CartController@index`
- `/checkout` ➔ `CheckoutController@index`
- `/sitemap.xml` ➔ `AdminAiAutomationController@sitemap`
- `/language/toggle` ➔ `LanguageController@toggle`
- `/api/live-chat/init`, `/api/live-chat/send`, `/api/live-chat/poll` ➔ `LiveChatController`

### ⚙️ ব্যাকএন্ড অ্যাডমিন প্যানেল (`/admin`):
- `/admin/dashboard` ➔ ড্যাশবোর্ড কেপিআই অ্যানালিটিক্স
- `/admin/orders` ➔ অর্ডার ম্যানেজমেন্ট ও কুরিয়ার বুকিং
- `/admin/live-chat` ➔ লাইভ সাপোর্ট ডেস্ক ও এআই অটো-পাইলট
- `/admin/ai-automation` ➔ এআই অটো-এসইও, হোয়াটসঅ্যাপ ভেরিফিকেশন ও ভয়েস ডায়ালার
- `/admin/products`, `/admin/categories` ➔ প্রোডাক্ট ও ইনভেন্টরি
- `/admin/analytics/pnl` ➔ নিট লাভ-ক্ষতি অডিট
- `/admin/gateways` ➔ কাস্টম পেমেন্ট গেটওয়ে হাব
- `/admin/marketing/pixels` ➔ ফেসবুক ও গুগল পিক্সেল হাব
- `/admin/abandoned-carts`, `/admin/sms` ➔ কার্ট রিকভারি ও এসএমএস

---

## 🧪 ৭. অটোমেটেড টেস্ট হিস্ট্রি (Test Suite)

- টেস্ট কমান্ড: `php artisan test`
- সর্বমোট টেস্ট সংখ্যা: **৩৩টি ফিচার টেস্ট (৩৩টিতেই ১০০% সফলভাবে পাস)**
- মোট অ্যাসারশন: **১৪০টি অ্যাসারশন**
- প্রধান টেস্ট ফাইলসমূহ:
  - `tests/Feature/EnterpriseAiAutomationTest.php` (Auto-SEO, WhatsApp Auto-Courier, Voice AI)
  - `tests/Feature/LiveChatAndAutoPilotTest.php` (Live Chat, Auto-Pilot Order Creation, Agent Desk)
  - `tests/Feature/ECommerceFlowTest.php` (Catalog, Cart, Checkout, Coupons)
  - `tests/Feature/OrderAndCourierTest.php` (Order Processing, Steadfast Courier Booking)
  - `tests/Feature/CustomGatewayTest.php` (Custom Payment Gateways Hub)

---

## 🚀 ৯. ব্যাকএন্ড রিফ্যাক্টরিং ও পরিপাটি এআই কন্ট্রোল সেন্টার (Recent Backend Refactoring)

- **পরিপাটি সাইডবার নেভিগেশন (Clean Sidebar Categories)**: জটিল মেনুগুলোকে ৭টি পরিষ্কার ও সুবিন্যস্ত বিভাগে ভাগ করা হয়েছে (ড্যাশবোর্ড, অর্ডার ও সেলস, প্রোডাক্ট ও স্টক, AI অটোমেশন হাব, মার্কেটিং ও সিআরএম, ওয়েবসাইট ডিজাইন, সিস্টেম সেটিংস)।
- **ইউনিফাইড AI কন্ট্রোল সেন্টার (`/admin/ai-automation`)**: এআই এর সকল জটিল ও ছড়িয়ে-ছিটিয়ে থাকা ফিচারগুলোকে ৪টি পরিষ্কার ও সহজ ইন্টারেক্টিভ ট্যাবে সুবিন্যস্ত করা হয়েছে:
  1. `💬 এআই সেলস চ্যাটবট (Auto-Pilot)`: ২৪/৭ চ্যাটে স্বয়ংক্রিয় অর্ডার বুকিং ব্যাখ্যা ও লাইভ সাপোর্ট ডেস্ক লিঙ্ক।
  2. `📱 WhatsApp ১-ক্লিক ভেরিফিকেশন`: ফ্রি `wa.me` মেসেজ প্রিভিউ ও 'হ্যাঁ' রিপ্লাইয়ে অটো-কুরিয়ার ডিসপ্যাচ সিমুলেটর।
  3. `📞 বাংলা এআই ভয়েস কলিং`: ব্রাউজার স্পিচ সিন্থেসিসের মাধ্যমে সরাসরি স্পষ্ট বাংলায় কাস্টমার কল ও অটো-কনফার্মেশন।
  4. `🚀 গুগল অটো-এসইও ও সাইটম্যাপ`: ১-ক্লিক এসইও সিঙ্ক ও গুগল রিচ স্নাইপেট প্রিভিউ।
- **মোবাইল বটম নেভিগেশন ও স্টোরফ্রন্ট আপগ্রেড**: মোবাইলের জন্য অ্যাপ-স্টাইল ৫-ট্যাব বটম বার, উইশলিস্ট হার্ট বাটন, পেজ রিলোড ছাড়া Ajax Add to Cart, আউট অব স্টক ব্যাজ ও মেগামেনু ড্রপডাউন।

---

## 🤖 ১০. এন্টারপ্রাইজ এআই ২.০ ও ভয়েস অ্যাসিস্ট্যান্স (Enterprise AI 2.0 Upgrade)

- **🎙️ ব্রাউজার ভয়েস ইনপুট (STT)**: চ্যাট উইজেটে মাইক্রোফোন বাটন দিয়ে গ্রাহক মুখে বলে (বাংলা ও ইংরেজি) সরাসরি অনুসন্ধান ও অর্ডার করতে পারেন।
- **🔊 ভয়েস স্পিচ প্লেব্যাক (TTS)**: প্রতিটি এআই উত্তরের পাশে স্পিকার বাটন দিয়ে গ্রাহক স্বাভাবিক বাংলায় কথা শুনতে পারেন।
- **🛒 ইন্টারেক্টিভ রিচ প্রোডাক্ট ক্যারোসেল**: গ্রাহক পণ্যের খোঁজ করলেই চ্যাটের ভেতরে সরাসরি ছবি, মূল্য, রেটিং ও ১-ক্লিক অর্ডার বাটনসহ প্রোডাক্ট কার্ড রেন্ডার হয়।
- **📦 ইন-চ্যাট লাইভ ট্র্যাকিং উইজেট**: অর্ডার বা ফোন নম্বর লিখলেই ৪-ধাপের ভিজ্যুয়াল প্রোগ্রেস বার চ্যাট বাবেলেই প্রদর্শিত হয়।
- **🎟️ ইন্টারেক্টিভ কুপন ও ভাউচার কার্ড**: কুপন সম্পর্কিত প্রশ্নে ১-ক্লিক কপি সুবিধা সহ কুপন কার্ড দেখায়।
- **📊 রিয়েল-টাইম এআই পারফরম্যান্স অ্যানালিটিক্স**: ড্যাশবোর্ডে এআই চ্যাট সেশন, কনভার্সন রেট, এআই জেনারেটেড মোট সেলস ও অর্ডার ট্র্যাকিং।
- **🎛️ এআই পারসোনা ও টেলিকম গেটওয়ে কনফিগারেশন**: এআই বট নাম, পারসোনা টোন (বিনম্র সেলস / টেক গুরু), কাস্টম গ্রিটিং, ম্যাক্সিমাম অটো-ডিসকাউন্ট লিমিট এবং রিয়েল টেলিকম গেটওয়ে (Twilio / Retell AI) ক্রেডেনশিয়ালস ম্যানেজমেন্ট।

---

## 📦 ১১. ম্যানুয়াল ও সোশ্যাল মিডিয়া অর্ডার এন্ট্রি এবং অটো-স্টক সিঙ্ক (Manual / FB / POS Orders)

- **মাল্টি-চ্যানেল উৎস নির্বাচন**: Facebook Messenger, WhatsApp, Direct Phone Call, Store POS অথবা Manual Entry চ্যানেল সিলেক্ট করে অর্ডার তৈরি করা যায়।
- **স্বয়ংক্রিয় স্টক সমন্বয় (Auto Stock Sync)**: ম্যানুয়ালি অর্ডার এন্ট্রি করার সাথে সাথে সিলেক্ট করা পণ্যের `stock_quantity` স্বয়ংক্রিয়ভাবে ডাটাবেস থেকে কমে যায় এবং `sales_count` বৃদ্ধি পায়।
- **অর্ডার বাতিল হলে স্টক পুনরুদ্ধার (Cancel Stock Restoration)**: কোনো কারণে অ্যাডমিন অর্ডার বাতিল (`cancelled`) করলে স্বয়ংক্রিয়ভাবে ঐ অর্ডারের প্রতিটি পণ্যের স্টক পুনরায় ইনভেন্টরিতে ফেরত যোগ হয়ে যায়।
- **কাস্টম ডিসকাউন্ট ও ডেলিভারি ফি ক্যালকুলেটর**: ঢাকা (৳৬০) বা ঢাকার বাইরে (৳১২০) স্বয়ংক্রিয় সিলেক্ট হয় এবং কাস্টম ছাড় সমন্বয়ের সুবিধা রয়েছে।

---

## 👥 ১২. অ্যাডভান্সড স্টাফ পারমিশন, কাস্টমার সিআরএম ও সেলস বুস্টার (User, Staff & Sales Boosters)

- **মডিউলভিত্তিক গ্র্যানুলার স্টাফ পারমিশন (Granular Role Permissions)**: প্রতিটি কর্মীকে নির্দিষ্ট বিভাগের অ্যাক্সেস (অর্ডার, প্রোডাক্ট, এআই চ্যাট, মার্কেটিং, সেটিংস, ফিন্যান্সিয়াল রিপোর্ট) দেওয়ার ব্যবস্থা।
- **কাস্টমার প্রোফাইল CRUD ও ১-ক্লিক ব্লক সুবিধা**: অ্যাডমিন থেকে নতুন গ্রাহক তৈরি, তথ্য সম্পাদনা এবং ফ্রড কাস্টমারকে এক ক্লিকে সাসপেন্ড/ব্লক করার ব্যবস্থা।
- **সোশ্যাল শেয়ার বাটন (Facebook & WhatsApp)**: প্রোডাক্ট পেজ থেকে সরাসরি ফেসবুক ও হোয়াটসঅ্যাপে লিঙ্ক শেয়ার এবং ১-ক্লিক কপি সুবিধা।
- **স্টক আরজেন্সি কাউন্টার ("সীমিত স্টক" অ্যালার্ট)**: পণ্যের স্টক ৫ বা তার কম হলে লাল রঙের লাইভ ফায়ার অ্যানিমেশন ও স্ট্যাটাস প্রদর্শন।
- **শপ ক্যাটালগ গ্রিড/লিস্ট ভিউ সুইচার**: কাস্টমার ১-ক্লিকে বক্স গ্রিড অথবা হরিজন্টাল লিস্ট ভিউতে পণ্য ব্রাউজ করতে পারেন।
- **অ্যাডমিন লো-স্টক নোটিফিকেশন বেল ও কুইক অর্ডার বাটন**: স্টক ৫ এর নিচে নামলেই অ্যাডমিন টপবারে লাল ব্লিঙ্কিং নোটিফিকেশন ব্যাজ।
