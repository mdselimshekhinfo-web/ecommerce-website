<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class EcommerceSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        $admin = User::create([
            'name' => 'Nexus Admin',
            'email' => 'admin@nexusdokan.bd',
            'password' => Hash::make('password'),
            'phone' => '01711000111',
            'address' => 'Gulshan-2, Dhaka-1212',
            'district' => 'Dhaka',
            'role' => 'admin',
            'is_admin' => true,
            'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',
        ]);

        $customer = User::create([
            'name' => 'Tanvir Ahmed',
            'email' => 'customer@nexusdokan.bd',
            'password' => Hash::make('password'),
            'phone' => '01812345678',
            'address' => 'House 42, Road 11, Banani',
            'district' => 'Dhaka',
            'role' => 'customer',
            'is_admin' => false,
            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80',
        ]);

        // 2. Categories
        $categories = [
            [
                'name' => 'Cyber Audio & ANC',
                'name_bn' => 'সাইবার অডিও ও হেডফোন',
                'slug' => 'cyber-audio-anc',
                'icon' => 'headphones',
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&auto=format&fit=crop&q=80',
                'description' => 'Ultra-low latency holographic audio gear with active noise cancellation.',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Smart Wearables & Neural',
                'name_bn' => 'স্মার্ট ওয়্যারেবলস ও ঘড়ি',
                'slug' => 'smart-wearables-neural',
                'icon' => 'watch',
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500&auto=format&fit=crop&q=80',
                'description' => 'Next-gen titanium smartwatches, health trackers and neural interface rings.',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Quantum Peripherals',
                'name_bn' => 'মেকানিক্যাল কিবোর্ড ও গেমিং গিয়ার',
                'slug' => 'quantum-peripherals',
                'icon' => 'keyboard',
                'image' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500&auto=format&fit=crop&q=80',
                'description' => 'Custom OLED mechanical keyboards, hall effect magnetic mice, and carbon pads.',
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Cyberpunk & Techwear',
                'name_bn' => 'সাইবারপাঙ্ক ও টেকওয়্যার ফ্যাশন',
                'slug' => 'cyberpunk-techwear',
                'icon' => 'shirt',
                'image' => 'https://images.unsplash.com/photo-1551488831-00ddcb6c6bd3?w=500&auto=format&fit=crop&q=80',
                'description' => 'Waterproof breathable techwear jackets, modular sling bags, and neon hoodies.',
                'is_featured' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Ambient & Smart Home LED',
                'name_bn' => 'অ্যাম্বিয়েন্ট লাইটিং ও স্মার্ট হোম',
                'slug' => 'ambient-smart-home',
                'icon' => 'sparkles',
                'image' => 'https://images.unsplash.com/photo-1507499739999-097706ad8914?w=500&auto=format&fit=crop&q=80',
                'description' => 'Neon modular LED hexagons, app-controlled aurora desk bars, and smart IoT plugs.',
                'is_featured' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Power & Fast Charging',
                'name_bn' => 'GaN ফাস্ট চার্জার ও পাওয়ার স্টেশন',
                'slug' => 'power-fast-charging',
                'icon' => 'zap',
                'image' => 'https://images.unsplash.com/photo-1622445262464-84b1456045b6?w=500&auto=format&fit=crop&q=80',
                'description' => '140W GaN transparent powerbanks, magnetic wireless charging docks, and cords.',
                'is_featured' => false,
                'sort_order' => 6,
            ],
        ];

        $catModels = [];
        foreach ($categories as $cat) {
            $catModels[$cat['slug']] = Category::create($cat);
        }

        // 3. Brands
        $brandsData = [
            ['name' => 'NexusCore', 'slug' => 'nexuscore', 'logo' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=100&auto=format&fit=crop&q=80', 'is_featured' => true],
            ['name' => 'NeuralWave', 'slug' => 'neuralwave', 'logo' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=100&auto=format&fit=crop&q=80', 'is_featured' => true],
            ['name' => 'ApexTitan', 'slug' => 'apextitan', 'logo' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=100&auto=format&fit=crop&q=80', 'is_featured' => true],
            ['name' => 'VortexBD', 'slug' => 'vortexbd', 'logo' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=100&auto=format&fit=crop&q=80', 'is_featured' => true],
            ['name' => 'LuminaCyber', 'slug' => 'luminacyber', 'logo' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=100&auto=format&fit=crop&q=80', 'is_featured' => true],
        ];

        $brandModels = [];
        foreach ($brandsData as $b) {
            $brandModels[$b['slug']] = Brand::create($b);
        }

        // 4. Products
        $products = [
            [
                'category_id' => $catModels['cyber-audio-anc']->id,
                'brand_id' => $brandModels['neuralwave']->id,
                'name' => 'AuraBlade ANC Cyber Earbuds Pro',
                'name_bn' => 'অরাব্লেড এএনসি সাইবার ইয়ারবাডস প্রো',
                'slug' => 'aurablade-anc-cyber-earbuds-pro',
                'sku' => 'NX-EAR-001',
                'short_description' => 'Transparent cyber casing with 45ms ultra-low gaming latency and 48dB active noise cancellation.',
                'short_description_bn' => 'স্বচ্ছ সাইবার কেসিং, ৪৫ মিলিসেকেন্ড আল্ট্রা-লো গেমিং লেটেন্সি এবং ৪৮ ডেসিবেল অ্যাক্টিভ নয়েজ ক্যান্সেলেশন।',
                'description' => 'Step into the next audio dimension with AuraBlade Pro. Engineered with custom 11mm beryllium composite drivers and military-grade hybrid ANC, you will hear crystal clear highs and deep rumbling bass. Includes RGB pulsing indicator on case and up to 40 hours of playtime.',
                'description_bn' => 'অরাব্লেড প্রো এর মাধ্যমে অডিওর ভবিষ্যৎ অনুভব করুন। ১১ মিমি টাইটানিয়াম ড্রাইভার ও হাইব্রিড নয়েজ ক্যান্সেলেশন আপনাকে দিবে ক্রিস্টাল ক্লিয়ার সাউন্ড ও ৪০ ঘণ্টার ব্যাটারি ব্যাকআপ। সাথে থাকছে সম্পূর্ণ ওয়াটারপ্রুফ রেটিং ও দ্রুত ফাস্ট চার্জিং সুবিধা।',
                'price' => 3800.00,
                'sale_price' => 2950.00,
                'stock_quantity' => 28,
                'thumbnail' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&auto=format&fit=crop&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1606220588913-b3aacb4d2f46?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1572536147248-ac59a8abfa4b?w=800&auto=format&fit=crop&q=80'
                ],
                'variants' => [
                    ['name' => 'Color', 'options' => ['Cyber Neon Cyan', 'Stealth Phantom Black', 'Mecha Silver White']],
                    ['name' => 'Edition', 'options' => ['Standard Edition', 'Pro Gamer Pack (+Extra Case)']]
                ],
                'specs' => [
                    'Bluetooth' => 'v5.4 Dual Stream',
                    'Battery Life' => '40 Hours with Case',
                    'Latency' => '45ms Ultra Low Latency',
                    'Water Resistance' => 'IPX5 Sweat & Water Proof',
                    'ANC Depth' => '-48dB Hybrid ANC',
                    'Warranty' => '1 Year Official Nexus Warranty'
                ],
                'badge' => '🔥 HOT SELLER',
                'is_featured' => true,
                'is_trending' => true,
                'is_flash_deal' => true,
                'flash_deal_end' => Carbon::now()->addDays(2),
                'rating' => 4.95,
                'reviews_count' => 42,
                'sales_count' => 189,
                'status' => 'active',
            ],
            [
                'category_id' => $catModels['smart-wearables-neural']->id,
                'brand_id' => $brandModels['nexuscore']->id,
                'name' => 'Chronos-X Holographic AMOLED Smartwatch',
                'name_bn' => 'ক্রোনস-এক্স হোলোগ্রাফিক অ্যামোলেড স্মার্টওয়াচ',
                'slug' => 'chronos-x-holographic-amoled-smartwatch',
                'sku' => 'NX-WTC-002',
                'short_description' => '2.04" curved 120Hz AMOLED with Aerospace Titanium frame and continuous ECG + SpO2 biosensors.',
                'short_description_bn' => '২.০৪ ইঞ্চি কার্ভড ১২০ হার্টজ অ্যামোলেড ডিসপ্লে, এরোস্পেস টাইটানিয়াম ফ্রেম এবং সার্বক্ষণিক হার্টরেট ও ইসিজি মনিটরিং।',
                'description' => 'Chronos-X is your ultimate wrist computer. Featuring Bluetooth calling with AI noise filtration, 100+ sports modes with Bangla UI font support, 14-day battery life, and wireless fast charging. Compatible with Android & iOS.',
                'description_bn' => 'ক্রোনস-এক্স হলো আপনার হাতের সুপার কম্পিউটার। ব্লুটুথ এইচডি কলিং, বাংলা ফন্ট সাপোর্ট, ১০০+ স্পোর্টস মোড এবং ১৪ দিনের দীর্ঘ ব্যাটারি লাইফ। সম্পূর্ণ ওয়াটারপ্রুফ মেটাল বডি।',
                'price' => 5500.00,
                'sale_price' => 4490.00,
                'stock_quantity' => 15,
                'thumbnail' => 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=800&auto=format&fit=crop&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1510017803434-a899398421b3?w=800&auto=format&fit=crop&q=80'
                ],
                'variants' => [
                    ['name' => 'Color', 'options' => ['Space Grey Titanium', 'Obsidian Black', 'Cyber Orange Sport']],
                    ['name' => 'Strap', 'options' => ['Magnetic Silicone', 'Titanium Link Chain']]
                ],
                'specs' => [
                    'Display' => '2.04" Sapphire AMOLED (1000 nits)',
                    'Sensors' => 'Optical ECG, SpO2, Sleep, Stress',
                    'Battery' => '450mAh (14 Days Normal Use)',
                    'Waterproof' => '5ATM / 50M Swimming Proof',
                    'Connectivity' => 'Bluetooth 5.3 + GPS Assisted',
                    'Warranty' => '1 Year Official Warranty'
                ],
                'badge' => '⚡ FLASH SALE',
                'is_featured' => true,
                'is_trending' => true,
                'is_flash_deal' => true,
                'flash_deal_end' => Carbon::now()->addDays(3),
                'rating' => 4.90,
                'reviews_count' => 38,
                'sales_count' => 145,
                'status' => 'active',
            ],
            [
                'category_id' => $catModels['quantum-peripherals']->id,
                'brand_id' => $brandModels['apextitan']->id,
                'name' => 'Vortex 75% Wireless Mechanical Keyboard (Gasket RGB)',
                'name_bn' => 'ভার্টেক্স ৭৫% ওয়্যারলেস মেকানিক্যাল কিবোর্ড (গ্যাসকেট আরজিবি)',
                'slug' => 'vortex-75-wireless-mechanical-keyboard',
                'sku' => 'NX-KBD-003',
                'short_description' => 'CNC Aluminum body, hot-swappable magnetic hall-effect switches, OLED mini display & volume knob.',
                'short_description_bn' => 'সিএনসি অ্যালুমিনিয়াম বডি, হট-সোয়াপ্যাবল সুইচ, কাস্টম ওএলইডি মিনি ডিসপ্লে এবং মাল্টিফাংশন ভলিউম নব।',
                'description' => 'The crown jewel of your futuristic desk setup. Featuring tri-mode connectivity (2.4GHz ultra-fast wireless, Bluetooth 5.2, and USB-C), 5-layer acoustic dampening foam for creamy thocky sound, and 22 per-key dynamic RGB lighting modes.',
                'description_bn' => 'আপনার ডেস্কে প্রিমিয়াম সাউন্ড ও ফিলের নিশ্চয়তা। ৩-টি মোডে কানেক্ট করার সুবিধা (ওয়্যারলেস ডঙ্গল, ব্লুটুথ এবং কেবল)। হট-সোয়াপ সুইচ এবং কাস্টমাইজযোগ্য ওএলইডি স্ক্রিন।',
                'price' => 7800.00,
                'sale_price' => 6450.00,
                'stock_quantity' => 12,
                'thumbnail' => 'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?w=800&auto=format&fit=crop&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1595225476474-87563907a212?w=800&auto=format&fit=crop&q=80'
                ],
                'variants' => [
                    ['name' => 'Switch Type', 'options' => ['Linear Creamy Yellow', 'Tactile Matcha Green', 'Silent Ghost White']],
                    ['name' => 'Case Color', 'options' => ['Cyberpunk Purple & Yellow', 'Minimalist Carbon Black', 'Mecha Silver White']]
                ],
                'specs' => [
                    'Layout' => '75% Compact (81 Keys + Knob)',
                    'Mount' => 'Multi-layer Silicone Gasket',
                    'Battery' => '8000mAh Dual-Cell (Up to 200h)',
                    'Switches' => 'Factory Pre-Lubed Custom Switches',
                    'Keycaps' => 'PBT Double-Shot Cherry Profile'
                ],
                'badge' => '⭐ TOP RATED',
                'is_featured' => true,
                'is_trending' => true,
                'is_flash_deal' => false,
                'rating' => 4.98,
                'reviews_count' => 54,
                'sales_count' => 210,
                'status' => 'active',
            ],
            [
                'category_id' => $catModels['cyberpunk-techwear']->id,
                'brand_id' => $brandModels['vortexbd']->id,
                'name' => 'NeoTokyo Waterproof Tactical Sling & Tech Bag',
                'name_bn' => 'নিওটোকিও ওয়াটারপ্রুফ ট্যাকটিক্যাল স্লিং ব্যাগ',
                'slug' => 'neotokyo-waterproof-tactical-sling-bag',
                'sku' => 'NX-BAG-004',
                'short_description' => 'Ballistic Cordura fabric with magnetic Fidlock buckle, hidden iPad pocket and reflective neon accents.',
                'short_description_bn' => 'ব্যালিস্টিক কর্ডুরা ফেব্রিক, ম্যাগনেটিক ফিডলক বাকল, আইপ্যাড পকেট এবং রাতের আলোয় রিফ্লেক্টিভ নিয়ন এক্সেন্ট।',
                'description' => 'Engineered for the urban explorer and gadget lover. Rainproof YKK zippers, modular Molle attachment system, padded breathable shoulder sling, and anti-theft RFID shielded pocket.',
                'description_bn' => 'শহুরে চলাফেরার জন্য পারফেক্ট টেক স্লিং ব্যাগ। সম্পূর্ণ ওয়াটারপ্রুফ ফেব্রিক, অ্যান্টি-থেফট পকেট এবং গ্যাজেট সুরক্ষিত রাখার বিশেষ কুশন পকেট।',
                'price' => 2400.00,
                'sale_price' => 1890.00,
                'stock_quantity' => 35,
                'thumbnail' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&auto=format&fit=crop&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?w=800&auto=format&fit=crop&q=80'
                ],
                'variants' => [
                    ['name' => 'Color', 'options' => ['Stealth Cyber Black', 'Neon Grid Shadow', 'Military Grey']]
                ],
                'specs' => [
                    'Material' => '1000D Cordura Waterproof Nylon',
                    'Capacity' => '6.5 Liters (Fits iPad 11")',
                    'Hardware' => 'German Fidlock Quick-Release Magnet',
                    'Zippers' => 'YKK AquaGuard Waterproof'
                ],
                'badge' => 'TRENDING',
                'is_featured' => true,
                'is_trending' => true,
                'is_flash_deal' => false,
                'rating' => 4.85,
                'reviews_count' => 29,
                'sales_count' => 112,
                'status' => 'active',
            ],
            [
                'category_id' => $catModels['ambient-smart-home']->id,
                'brand_id' => $brandModels['luminacyber']->id,
                'name' => 'HexaGlow Smart Modular RGB Wall Panels (9-Pack)',
                'name_bn' => 'হেক্সাগ্লো স্মার্ট মডুলার আরজিবি ওয়াল প্যানেল (৯ পিস)',
                'slug' => 'hexaglow-smart-modular-rgb-wall-panels',
                'sku' => 'NX-LED-005',
                'short_description' => 'Music rhythm sync, 16 million colors, touch capacitive control and Google Home/Alexa/App integration.',
                'short_description_bn' => 'গানের সাথে রিদম সিঙ্ক, ১৬ মিলিয়ন কালার অপশন, টাচ কন্ট্রোল এবং স্মার্টফোন অ্যাপ দিয়ে সম্পূর্ণ নিয়ন্ত্রণ।',
                'description' => 'Transform your bedroom or gaming studio into a futuristic spaceship. Create custom geometric patterns on your wall. Built-in high-sensitivity mic pulses with your music and in-game explosions in real time.',
                'description_bn' => 'আপনার রুমের ব্যাকড্রপকে করে তুলুন এক অপার্থিব ফিউচারিস্টিক স্পেস। মোবাইল অ্যাপের মাধ্যমে ১৬ মিলিয়ন রঙ ও মিউজিক সিনক্রোনাইজেশন উপভোগ করুন।',
                'price' => 4900.00,
                'sale_price' => 3850.00,
                'stock_quantity' => 18,
                'thumbnail' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=800&auto=format&fit=crop&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1518770660439-4636190af475?w=800&auto=format&fit=crop&q=80'
                ],
                'variants' => [
                    ['name' => 'Pack Size', 'options' => ['9 Panels Starter Kit', '15 Panels Mega Gamer Kit (+৳2200)']]
                ],
                'specs' => [
                    'Connectivity' => 'Wi-Fi 2.4GHz + Bluetooth BLE',
                    'Control' => 'Touch Sensor + iOS/Android App + Voice',
                    'Lighting' => '16M DreamColor ARGB IC LEDs',
                    'Power' => 'USB 5V/2A Adapter Included'
                ],
                'badge' => 'VIRAL TIKTOK',
                'is_featured' => true,
                'is_trending' => true,
                'is_flash_deal' => true,
                'flash_deal_end' => Carbon::now()->addDays(1),
                'rating' => 4.92,
                'reviews_count' => 47,
                'sales_count' => 176,
                'status' => 'active',
            ],
            [
                'category_id' => $catModels['power-fast-charging']->id,
                'brand_id' => $brandModels['nexuscore']->id,
                'name' => 'MechaCharge 130W Cyberpunk Transparent Powerbank (20,000mAh)',
                'name_bn' => 'মেকাচার্জ ১৩০ ওয়াট সাইবারপাঙ্ক ট্রান্সপারেন্ট পাওয়ারব্যাংক',
                'slug' => 'mechacharge-130w-cyberpunk-transparent-powerbank',
                'sku' => 'NX-PWR-006',
                'short_description' => 'Real-time IPS status screen showing watts, volts, battery health, dual 100W PD Type-C + USB-A ports.',
                'short_description_bn' => 'রিয়েল-টাইম আইপিএস স্ক্রিনে লাইভ ওয়াট ও ভোল্টেজ ট্র্যাকিং, ১০০ ওয়াট ল্যাপটপ চার্জিং ও ২০,০০০ এমএএইচ ব্যাটারি।',
                'description' => 'Charge your MacBook Pro, iPhone, and Android flagship at maximum speed simultaneously. Futuristic see-through transparent glass casing shows high-precision gold circuit boards and active safety chips.',
                'description_bn' => 'ল্যাপটপ ও ফোন একই সাথে সুপার ফাস্ট চার্জ করার জন্য সবচেয়ে সেরা সাইবার পাওয়ারব্যাংক। লাইভ স্ক্রিনে চার্জিং ওয়াট দেখতে পারবেন। এয়ারপ্লেন সেফ ও প্রিমিয়াম বিল্ড।',
                'price' => 4500.00,
                'sale_price' => 3650.00,
                'stock_quantity' => 22,
                'thumbnail' => 'https://images.unsplash.com/photo-1609592426505-728b7e289bf5?w=800&auto=format&fit=crop&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1609592426505-728b7e289bf5?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1583863788434-e58a36330cf0?w=800&auto=format&fit=crop&q=80'
                ],
                'variants' => [
                    ['name' => 'Color', 'options' => ['Cyberpunk Gold Yellow', 'Matrix Ghost Clear', 'Cyber Phantom Dark']]
                ],
                'specs' => [
                    'Capacity' => '20,000mAh (74Wh Airline Safe)',
                    'Max Output' => '130W Total (100W Single Port PD 3.0)',
                    'Screen' => 'Color TFT Smart Power Display',
                    'Recharge Time' => 'Fully charged in 90 minutes with 65W charger',
                    'Warranty' => '1 Year Official Warranty'
                ],
                'badge' => 'BEST VALUE',
                'is_featured' => true,
                'is_trending' => true,
                'is_flash_deal' => false,
                'rating' => 4.88,
                'reviews_count' => 31,
                'sales_count' => 98,
                'status' => 'active',
            ],
            [
                'category_id' => $catModels['cyber-audio-anc']->id,
                'brand_id' => $brandModels['neuralwave']->id,
                'name' => 'SpectraSound Hi-Res Wireless ANC Over-Ear Headphones',
                'name_bn' => 'স্পেক্ট্রাসাউন্ড হাই-রেস ওয়্যারলেস এএনসি হেডফোন',
                'slug' => 'spectrasound-hi-res-wireless-anc-headphones',
                'sku' => 'NX-HDP-007',
                'short_description' => '40mm gold-plated planar drivers, memory-foam cooling gel cups, spatial 3D audio & 60h battery.',
                'short_description_bn' => '৪০ মিমি প্ল্যানার ড্রাইভার, কুলিং জেল মেমরি ফোম ইয়ারপ্যাড, স্প্যাশিয়াল থ্রিডি অডিও এবং ৬০ ঘণ্টার ব্যাটারি লাইফ।',
                'description' => 'Studio grade acoustics engineered for audiophiles and gamers. Active hybrid noise reduction cancels 95% of background ambient city sound. Foldable ergonomic design with detachable braided audio cable.',
                'description_bn' => 'স্টুডিও কোয়ালিটি সাউন্ড এবং নিখুঁত নয়েজ ক্যান্সেলেশন। নরম মেমরি ফোম ইয়ারকাপ আপনাকে দীর্ঘ সময় ক্লান্তহীন আরাম দেবে। সাথে রয়েছে ব্লুটুথ ও কেবলের ডুয়েল অপশন।',
                'price' => 6200.00,
                'sale_price' => 4950.00,
                'stock_quantity' => 10,
                'thumbnail' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=800&auto=format&fit=crop&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=800&auto=format&fit=crop&q=80'
                ],
                'variants' => [
                    ['name' => 'Color', 'options' => ['Cyber Carbon Black', 'Polar Ice Silver', 'Midnight Neon Blue']]
                ],
                'specs' => [
                    'Drivers' => '40mm Silk Diaphragm Hi-Res Certified',
                    'Playtime' => '60 Hours (45 Hours with ANC ON)',
                    'Microphone' => 'Quad AI Beamforming Noise Reduction',
                    'Charging' => 'USB-C (10 min charge = 5 hours play)'
                ],
                'badge' => 'PREMIUM',
                'is_featured' => true,
                'is_trending' => false,
                'is_flash_deal' => false,
                'rating' => 4.96,
                'reviews_count' => 26,
                'sales_count' => 84,
                'status' => 'active',
            ],
            [
                'category_id' => $catModels['quantum-peripherals']->id,
                'brand_id' => $brandModels['apextitan']->id,
                'name' => 'Phantom-X 8K Polling Rate Ultra-light Wireless Gaming Mouse',
                'name_bn' => 'ফ্যান্টম-এক্স ৮কে পোলিং রেট আল্ট্রা-লাইট ওয়্যারলেস মাউস',
                'slug' => 'phantom-x-8k-wireless-gaming-mouse',
                'sku' => 'NX-MOU-008',
                'short_description' => '49 grams magnesium alloy skeleton, PAW3395 26,000 DPI sensor and true 8000Hz polling rate.',
                'short_description_bn' => 'মাত্র ৪৯ গ্রাম ম্যাগনেসিয়াম অ্যালয় বডি, ২৬০০০ ডিপিআই সেন্সর এবং ৮০০০ হার্টজ আল্ট্রা ফাস্ট রেসপন্স।',
                'description' => 'Built for competitive FPS champions in Bangladesh. Zero input delay, optical microswitches rated for 100 million clicks, and pure PTFE virgin mouse skates for effortless gliding.',
                'description_bn' => 'গেমিংয়ে সবচেয়ে নিখুঁত ও দ্রুততম নিশানার জন্য তৈরি। সুপার লাইটওয়েট এবং ১০০ মিলিয়ন ক্লিক লাইফস্প্যান সম্পন্ন অপটিক্যাল সুইচ।',
                'price' => 4200.00,
                'sale_price' => 3350.00,
                'stock_quantity' => 25,
                'thumbnail' => 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=800&auto=format&fit=crop&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=800&auto=format&fit=crop&q=80'
                ],
                'variants' => [
                    ['name' => 'Color', 'options' => ['Matte Cyber White', 'Anodized Carbon Black', 'Cyberpunk Gold']]
                ],
                'specs' => [
                    'Weight' => '49g Ultra-Lightweight',
                    'Sensor' => 'PixArt PAW3395 Optical (26,000 DPI)',
                    'Polling Rate' => '8,000Hz Wireless with 8K Dongle Included',
                    'Battery' => 'Up to 90 hours non-stop play'
                ],
                'badge' => '8K ULTRA',
                'is_featured' => true,
                'is_trending' => true,
                'is_flash_deal' => false,
                'rating' => 4.89,
                'reviews_count' => 34,
                'sales_count' => 130,
                'status' => 'active',
            ],
        ];

        $productModels = [];
        foreach ($products as $p) {
            $productModels[] = Product::create($p);
        }

        // 5. Coupons
        Coupon::create([
            'code' => 'CYBER10',
            'description' => '10% discount on any order over ৳1,000',
            'type' => 'percentage',
            'value' => 10.00,
            'min_spend' => 1000.00,
            'max_discount' => 500.00,
            'usage_limit' => 500,
            'used_count' => 23,
            'expires_at' => Carbon::now()->addMonths(6),
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'NEXUS200',
            'description' => 'Instant ৳200 Flat Discount',
            'type' => 'fixed',
            'value' => 200.00,
            'min_spend' => 1500.00,
            'max_discount' => 200.00,
            'usage_limit' => 200,
            'used_count' => 45,
            'expires_at' => Carbon::now()->addMonths(3),
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'SPIN500',
            'description' => 'Lucky Cyber Wheel ৳500 Special Voucher',
            'type' => 'fixed',
            'value' => 500.00,
            'min_spend' => 3000.00,
            'max_discount' => 500.00,
            'usage_limit' => 100,
            'used_count' => 12,
            'expires_at' => Carbon::now()->addMonths(1),
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'FREESHIPBD',
            'description' => 'Free Delivery Anywhere in Bangladesh',
            'type' => 'fixed',
            'value' => 120.00,
            'min_spend' => 2000.00,
            'max_discount' => 120.00,
            'usage_limit' => 1000,
            'used_count' => 88,
            'expires_at' => Carbon::now()->addMonths(12),
            'is_active' => true,
        ]);

        // 6. Reviews
        $reviews = [
            [
                'product_id' => $productModels[0]->id,
                'customer_name' => 'Saiful Islam',
                'customer_location' => 'Mirpur, Dhaka',
                'rating' => 5,
                'comment' => 'অসাধারণ সাউন্ড কোয়ালিটি! বিশেষ করে বেস এবং এএনসি ফিচার সত্যিই চোখ ধাঁধানো। বিকাশ দিয়ে পেমেন্ট করেছিলাম, ১ দিনের মাথায় ঢাকায় ডেলিভারি পেয়েছি।',
                'is_verified_purchase' => true,
                'status' => true,
            ],
            [
                'product_id' => $productModels[0]->id,
                'customer_name' => 'Fahim Shahriar',
                'customer_location' => 'Agrabad, Chattogram',
                'rating' => 5,
                'comment' => 'Transparent cyber case looks insane in hand! Gaming latency is nonexistent. Totally worth every Taka.',
                'is_verified_purchase' => true,
                'status' => true,
            ],
            [
                'product_id' => $productModels[1]->id,
                'customer_name' => 'Nusrat Jahan',
                'customer_location' => 'Uttara, Dhaka',
                'rating' => 5,
                'comment' => 'ডিসপ্লে ব্রাইটনেস রোদেও স্পষ্ট দেখা যায়। বাংলা নোটিফিকেশন খুব সুন্দর দেখায়। ঘড়িটার লুক এককথায় ফিউচারিস্টিক!',
                'is_verified_purchase' => true,
                'status' => true,
            ],
            [
                'product_id' => $productModels[2]->id,
                'customer_name' => 'Arif Chowdhury',
                'customer_location' => 'Sylhet Sadar',
                'rating' => 5,
                'comment' => 'Keyboard typing sound is so creamy (pure thock)! Steadfast courier delivered safely to Sylhet within 48 hours.',
                'is_verified_purchase' => true,
                'status' => true,
            ],
        ];

        foreach ($reviews as $rev) {
            Review::create($rev);
        }

        // 7. Demo Orders
        $order1 = Order::create([
            'order_number' => 'NX-2026-9812',
            'user_id' => $customer->id,
            'customer_name' => 'Tanvir Ahmed',
            'customer_email' => 'customer@nexusdokan.bd',
            'customer_phone' => '01812345678',
            'delivery_district' => 'Dhaka',
            'delivery_address' => 'House 42, Road 11, Block D, Banani, Dhaka-1213',
            'delivery_notes' => 'Please call before arriving at the gate.',
            'shipping_zone' => 'inside_dhaka',
            'shipping_cost' => 60.00,
            'subtotal' => 2950.00,
            'discount_amount' => 200.00,
            'coupon_code' => 'NEXUS200',
            'total_amount' => 2810.00,
            'payment_method' => 'bkash',
            'payment_status' => 'paid',
            'transaction_id' => 'BKS9X84N72A',
            'order_status' => 'shipped',
            'courier_name' => 'Steadfast Courier BD',
            'tracking_code' => 'STF-BD-904812',
            'admin_notes' => 'Dispatched with priority express packaging.',
            'created_at' => Carbon::now()->subHours(18),
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $productModels[0]->id,
            'product_name' => 'AuraBlade ANC Cyber Earbuds Pro',
            'product_image' => $productModels[0]->thumbnail,
            'price' => 2950.00,
            'quantity' => 1,
            'variant_info' => 'Color: Cyber Neon Cyan',
            'total' => 2950.00,
        ]);

        $order2 = Order::create([
            'order_number' => 'NX-2026-9813',
            'user_id' => null,
            'customer_name' => 'Mahmud Hasan',
            'customer_email' => 'mahmud@gmail.com',
            'customer_phone' => '01799887766',
            'delivery_district' => 'Chattogram',
            'delivery_address' => 'GEC Circle, Nasirabad, Chattogram',
            'delivery_notes' => 'Call after 2 PM',
            'shipping_zone' => 'outside_dhaka',
            'shipping_cost' => 120.00,
            'subtotal' => 4490.00,
            'discount_amount' => 0.00,
            'coupon_code' => null,
            'total_amount' => 4610.00,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'transaction_id' => null,
            'order_status' => 'processing',
            'courier_name' => 'Pathao Express',
            'tracking_code' => 'PTH-CTG-67123',
            'admin_notes' => 'Customer confirmed via phone call.',
            'created_at' => Carbon::now()->subHours(5),
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $productModels[1]->id,
            'product_name' => 'Chronos-X Holographic AMOLED Smartwatch',
            'product_image' => $productModels[1]->thumbnail,
            'price' => 4490.00,
            'quantity' => 1,
            'variant_info' => 'Color: Space Grey Titanium',
            'total' => 4490.00,
        ]);
    }
}
