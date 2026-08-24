<?php

namespace App\Helpers;

use App\Models\ThemeSetting;
use App\Models\SiteSection;

class ThemeDefaults
{
    public static function seedDefaults()
    {
        // 1. Comprehensive Global A to Z Theme Settings
        $settings = [
            // Branding & Logo
            'logo_type' => 'text', // 'text' or 'image'
            'site_name' => 'NEXUS DOKAN',
            'site_tagline' => 'NEXT-GEN ECOMMERCE BD',
            'logo_icon' => 'cpu', // Lucide icon
            'logo_image_url' => '',
            'favicon_url' => '',
            
            // Colors & Appearance
            'primary_neon_color' => '#00f2fe',
            'secondary_neon_color' => '#ff007f',
            'accent_purple_color' => '#7928ca',
            'accent_green_color' => '#00ff88',
            'accent_gold_color' => '#fbbf24',
            'bg_dark_color' => '#07080e',
            'card_bg_color' => '#0e111d',

            // Header & Marquee Tickers
            'enable_top_ticker' => '1',
            'ticker_text_1' => '⚡ FLASH SALE: Up to 50% OFF on Neural Wearables & Mechanical Peripherals!',
            'ticker_text_2' => '🇧🇩 Free Shipping Anywhere in Bangladesh on orders ৳2,000+ (Code: FREESHIPBD)',
            'ticker_text_3' => '📱 bKash & Nagad Direct Seamless Instant Checkout',
            'enable_language_switcher' => '1',

            // Shop & Catalog
            'products_per_page' => '12',
            'currency_symbol' => '৳',
            'currency_code' => 'BDT',

            // Bangladesh Logistics & Delivery Charges
            'delivery_charge_dhaka' => '60',
            'delivery_charge_sub_dhaka' => '80',
            'delivery_charge_outside' => '120',
            'free_shipping_threshold' => '2000',
            'estimated_delivery_dhaka' => '24 Hours',
            'estimated_delivery_outside' => '48-72 Hours',

            // Bangladeshi Payment Gateways
            'enable_bkash' => '1',
            'enable_nagad' => '1',
            'enable_rocket' => '1',
            'enable_cod' => '1',
            'enable_card' => '1',
            'bkash_merchant_number' => '01711000111',
            'nagad_merchant_number' => '01811000222',
            'bkash_instructions' => 'Authorize instant payment via bKash or input your TrxID.',
            'nagad_instructions' => 'Authorize instant payment via Nagad or input your TrxID.',

            // Footer, Office & Contacts
            'store_about_text' => 'Bangladesh\'s premier cyber-luxe tech store. Specializing in high-performance cyber wearables, ANC audio gear, mechanical battlestation setups, and fast express delivery across all 64 districts.',
            'hotline_phone' => '+880 1711-000111',
            'support_email' => 'support@nexusdokan.bd',
            'store_address' => 'Level 6, Cyber Hub, Gulshan-2, Dhaka-1212',
            'vat_bin_number' => 'BIN: 00491823-0101 (VAT Registered)',
            'copyright_text' => '© ' . date('Y') . ' NEXUS DOKAN BD. All rights reserved. Crafted with futuristic passion.',
            'facebook_url' => 'https://facebook.com',
            'instagram_url' => 'https://instagram.com',
            'youtube_url' => 'https://youtube.com',
            'whatsapp_number' => '+8801711000111',

            // Interactive Features Toggles
            'enable_lucky_wheel' => '1',
            'lucky_wheel_title' => 'SPIN & WIN VOUCHERS',
            'lucky_wheel_subtitle' => 'Spin to unlock instant discount coupons up to ৳500 or Free Shipping across BD!',
            'enable_ai_assistant' => '1',
            'ai_bot_name' => 'AURA CYBER AI',
            'ai_welcome_message' => '👋 Hello! I am **Aura**, your AI Cyber Assistant. Looking for anything in Bangladesh? Ask me about products, delivery fees, bKash checkout or active coupon codes!',
            'enable_social_proof' => '1',
        ];

        foreach ($settings as $k => $v) {
            ThemeSetting::updateOrCreate(['key' => $k], ['value' => $v]);
        }

        // 2. Sections
        $sections = [
            [
                'section_key' => 'hero',
                'name' => 'Hero Hologram Banner',
                'is_active' => true,
                'sort_order' => 1,
                'content' => [
                    'badge' => '⚡ NEXT-GEN GADGETS & LIFESTYLE • BANGLADESH',
                    'title_line_1' => 'DISCOVER THE',
                    'title_gradient' => 'CYBER REVOLUTION',
                    'subtitle' => 'Elevate your life with ultra-responsive mechanical setups, holographic smart wearables, planar ANC audio, and GaN power stations. Delivered across Bangladesh within 24-48 hours.',
                    'btn1_text' => 'Explore Catalog',
                    'btn1_link' => '/shop',
                    'btn2_text' => 'Spin & Win ৳500',
                    'stat1_num' => '50K+',
                    'stat1_label' => 'BD Customers',
                    'stat2_num' => '24H',
                    'stat2_label' => 'Dhaka Express',
                    'stat3_num' => '100%',
                    'stat3_label' => 'Genuine Tech',
                    'featured_card_badge' => '🔥 TOP CYBER GEAR',
                    'featured_card_title' => 'AuraBlade ANC Pro',
                    'featured_card_sub' => '45ms Low Latency • 48dB ANC',
                    'featured_card_price' => '৳2,950 BDT',
                    'featured_card_img' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=600&auto=format&fit=crop&q=80',
                    'featured_card_link' => '/product/aurablade-anc-cyber-earbuds-pro',
                ]
            ],
            [
                'section_key' => 'flash_sale',
                'name' => 'Flash Sale Matrix',
                'is_active' => true,
                'sort_order' => 2,
                'content' => [
                    'title' => 'FLASH SALE MATRIX',
                    'subtitle' => 'Limited quantity cyber deals with instant discounts',
                    'hours' => '08',
                    'minutes' => '42',
                    'seconds' => '19',
                ]
            ],
            [
                'section_key' => 'categories',
                'name' => 'Cyber Categories Showcase',
                'is_active' => true,
                'sort_order' => 3,
                'content' => [
                    'title' => 'EXPLORE CYBER CATEGORIES',
                    'subtitle' => 'Handpicked next-gen gadgets and lifestyle accessories built for Bangladeshi tech lovers.',
                ]
            ],
            [
                'section_key' => 'trending',
                'name' => 'Trending Cyber Gear',
                'is_active' => true,
                'sort_order' => 4,
                'content' => [
                    'title' => 'TRENDING CYBER GEAR',
                    'subtitle' => 'High rating peripherals and wearables loved by shoppers across Bangladesh',
                ]
            ],
            [
                'section_key' => 'promo_banner',
                'name' => 'Cyber Callout Promo Banner',
                'is_active' => true,
                'sort_order' => 5,
                'content' => [
                    'title' => 'UPGRADE YOUR BATTLESTATION 2026',
                    'subtitle' => 'Get extra 15% instant cashback when paying with bKash or Nagad. Use code at checkout.',
                    'coupon_badge' => 'CYBER10',
                    'btn_text' => 'Shop Cyber Audio & Keyboards',
                    'btn_link' => '/shop',
                    'bg_glow_color' => '#7928ca',
                ]
            ],
            [
                'section_key' => 'trust_badges',
                'name' => 'Bangladesh Trust & Perks',
                'is_active' => true,
                'sort_order' => 6,
                'content' => [
                    'card1_title' => '24-48H Fast BD Delivery',
                    'card1_desc' => 'Express 24h courier across Dhaka (৳60) and 48h reliable delivery across all 64 districts (৳120).',
                    'card1_icon' => 'truck',
                    'card2_title' => 'bKash & COD Ready',
                    'card2_desc' => 'Seamless bKash & Nagad checkout or pay Cash on Delivery at your doorstep anywhere in Bangladesh.',
                    'card2_icon' => 'credit-card',
                    'card3_title' => 'Official Warranty',
                    'card3_desc' => '100% genuine guaranteed with up to 1-Year official warranty and 7-day hassle-free replacement.',
                    'card3_icon' => 'shield-check',
                    'card4_title' => 'Spin & Win Rewards',
                    'card4_desc' => 'Gamified lucky spin vouchers to earn real discount coupons on every visit.',
                    'card4_icon' => 'sparkles',
                ]
            ],
            [
                'section_key' => 'reviews',
                'name' => 'Customer Reviews & Social Proof',
                'is_active' => true,
                'sort_order' => 7,
                'content' => [
                    'title' => 'WHAT CYBER SHOPPERS SAY',
                    'badge' => 'VERIFIED BD SHOPPER REVIEWS',
                ]
            ],
        ];

        foreach ($sections as $sec) {
            SiteSection::updateOrCreate(
                ['section_key' => $sec['section_key']],
                [
                    'name' => $sec['name'],
                    'is_active' => $sec['is_active'],
                    'sort_order' => $sec['sort_order'],
                    'content' => $sec['content'],
                ]
            );
        }
    }
}
