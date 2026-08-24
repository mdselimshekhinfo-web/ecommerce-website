<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomPage;
use App\Models\LandingPage;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ThemeSetting;

class MarketingSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Default Policy Pages
        $pages = [
            [
                'title' => 'রিটার্ন ও রিফান্ড পলিসি (Return & Refund Policy)',
                'slug' => 'return-refund-policy',
                'content' => '<h2>৭ দিনের সহজ রিটার্ন পলিসি</h2><p>NEXUS DOKAN থেকে কেনা যেকোনো ত্রুটিপূর্ণ বা ড্যামেজড প্রোডাক্ট ডেলিভারি পাওয়ার ৭ দিনের মধ্যে সম্পূর্ণ ফ্রিতে রিটার্ন বা রিপ্লেস করা যাবে।</p><h3>রিটার্নের শর্তাবলী:</h3><ul><li>প্রোডাক্টের মূল বক্স ও সকল এক্সেসরিজ অক্ষত থাকতে হবে।</li><li>ডেলিভারিম্যানের উপস্থিতিতে পার্সেল চেক করে গ্রহণ করার অনুরোধ রইল।</li><li>রিফান্ড অনুমোদনের ২৪-৪৮ ঘণ্টার মধ্যে বিকাশ/নগদে টাকা ফেরত দেওয়া হবে।</li></ul>',
                'is_footer_link' => true,
                'status' => 'published',
            ],
            [
                'title' => 'শিপিং ও ডেলিভারি শর্তাবলী (Shipping Terms)',
                'slug' => 'shipping-terms',
                'content' => '<h2>সারা বাংলাদেশে ফাস্ট ডেলিভারি</h2><p>আমরা Steadfast ও Pathao এক্সপ্রেস কুরিয়ারের মাধ্যমে বাংলাদেশের ৬৪টি জেলাতেই হোম ডেলিভারি প্রদান করি।</p><ul><li><b>ঢাকার ভেতরে:</b> ২৪ থেকে ৪৮ ঘণ্টার মধ্যে ডেলিভারি (চার্জ: ৳৬০)।</li><li><b>ঢাকার বাইরে / সকল জেলায়:</b> ২ থেকে ৩ কার্যদিবসের মধ্যে ডেলিভারি (চার্জ: ৳১২০)।</li><li>৳২,০০০ এর বেশি অর্ডারে ফ্রি ডেলিভারি।</li></ul>',
                'is_footer_link' => true,
                'status' => 'published',
            ],
            [
                'title' => 'প্রাইভেসি পলিসি (Privacy Policy)',
                'slug' => 'privacy-policy',
                'content' => '<h2>আপনার তথ্যের পূর্ণ নিরাপত্তা</h2><p>NEXUS DOKAN আপনার ব্যক্তিগত তথ্যের (নাম, ফোন, ঠিকানা) সর্বোচ্চ সুরক্ষা নিশ্চিত করে। আপনার তথ্য শুধুমাত্র অর্ডার প্রসেসিং ও ডেলিভারির প্রয়োজনে ব্যবহৃত হয় এবং কোনো তৃতীয় পক্ষের কাছে হস্তান্তর করা হয় না।</p>',
                'is_footer_link' => true,
                'status' => 'published',
            ],
            [
                'title' => 'আমাদের সম্পর্কে (About Us)',
                'slug' => 'about-us',
                'content' => '<h2>NEXUS DOKAN // Next-Gen Cyber eCommerce</h2><p>আমরা বাংলাদেশের প্রিমিয়াম ফিউচারিস্টিক গ্যাজেট, মেকানিক্যাল কীবোর্ড, এএনসি হেডফোন ও সাইবার লাইফস্টাইল অ্যাক্সেসরিজের বিশ্বস্ত হাব। আমাদের হেড অফিস গুলশান-২, ঢাকায় অবস্থিত।</p>',
                'is_footer_link' => true,
                'status' => 'published',
            ],
        ];

        foreach ($pages as $p) {
            CustomPage::updateOrCreate(['slug' => $p['slug']], $p);
        }

        // 2. Default Pixel & Tracking IDs in ThemeSetting
        $pixelDefaults = [
            'fb_pixel_id' => '102938475619283',
            'fb_capi_token' => '',
            'gtm_id' => 'GTM-554S4282',
            'ga4_id' => 'G-NXBD849120',
            'tiktok_pixel_id' => '',
            'header_custom_code' => '<!-- Google Analytics & Pixel Ready -->',
            'footer_custom_code' => '',
            // Courier APIs
            'steadfast_api_key' => 'stf_live_api_948102381204',
            'steadfast_secret_key' => 'stf_sec_891238491024',
            'pathao_client_id' => '',
            'pathao_secret_key' => '',
            // SMS APIs
            'sms_api_key' => 'gw_live_sms_89412093481',
            'sms_sender_id' => 'NEXUS DOKAN',
        ];

        foreach ($pixelDefaults as $key => $val) {
            ThemeSetting::updateOrCreate(['key' => $key], ['value' => $val]);
        }

        // 3. Demo High-Converting 1-Page Landing Page
        $earbuds = Product::where('slug', 'aurablade-anc-cyber-earbuds-pro')->first();
        LandingPage::updateOrCreate([
            'slug' => 'aurablade-cyber-earbuds'
        ], [
            'title' => 'AuraBlade ANC Cyber Earbuds Pro // 1-Page Special Flash Deal',
            'product_id' => $earbuds ? $earbuds->id : null,
            'headline' => 'বাংলাদেশে এই প্রথম -45dB অ্যাক্টিভ নয়েজ ক্যান্সেলেশন সহ সাইবার ইয়ারবাডস!',
            'subheadline' => 'হোলোগ্রাফিক নিয়ন এলইডি ডিসপ্লে, ৪০ ঘণ্টার সুপার ব্যাটারি লাইফ এবং আল্ট্রা-লো লেটেন্সি গেমিং মোড।',
            'offer_price' => 2450.00,
            'regular_price' => 3200.00,
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'banner_image' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=1200&auto=format&fit=crop&q=80',
            'features_list' => [
                '🎯 -45dB হাইব্রিড অ্যাক্টিভ নয়েজ ক্যান্সেলেশন (ANC)',
                '🔋 ৪০ ঘণ্টার লং-লাস্টিং ব্যাটারি ও টাইপ-সি ফাস্ট চার্জিং',
                '⚡ ৩৮ms আল্ট্রা-লো লেটেন্সি ডেডিকেটেড গেমিং মোড',
                '💎 প্রিমিয়াম মেটালিক সাইবারপঙ্ক ডিজাইনের ম্যাগনেটিক কেস',
                '🇧🇩 সারা বাংলাদেশে ক্যাশ অন ডেলিভারি (পার্সেল দেখে টাকা পরিশোধ)'
            ],
            'countdown_end_time' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'status' => 'active',
        ]);

        // 4. Demo Customer Reviews
        if ($earbuds) {
            ProductReview::create([
                'product_id' => $earbuds->id,
                'reviewer_name' => 'Tanvir Ahmed',
                'reviewer_phone' => '01711223344',
                'rating' => 5,
                'comment' => 'অসাধারণ সাউন্ড কোয়ালিটি ও বেস! এএনসি নয়েজ ক্যান্সেলেশন একদম ট্রু ফিলিংস দেয়। ঢাকায় ২৪ ঘণ্টার মধ্যে হাতে পেয়েছি।',
                'is_verified_purchase' => true,
                'status' => 'approved',
                'created_at' => now()->subDays(2),
            ]);

            ProductReview::create([
                'product_id' => $earbuds->id,
                'reviewer_name' => 'Mehedi Hasan',
                'reviewer_phone' => '01819887766',
                'rating' => 5,
                'comment' => 'লুকটা জোস! নিয়ন লাইটিং ও ব্লুটুথ ৫.৪ কানেক্টিভিটি সুপার ফাস্ট। ১০০% রেকমেন্ডেড।',
                'is_verified_purchase' => true,
                'status' => 'approved',
                'created_at' => now()->subDay(),
            ]);
        }
    }
}
