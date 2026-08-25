<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThemeSetting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = ThemeSetting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $keys = [
            // Social Media & Channels
            'facebook_url',
            'facebook_page_username',
            'facebook_messenger_url',
            'enable_messenger_floating',
            'enable_whatsapp_floating',
            'whatsapp_number',
            'instagram_url',
            'tiktok_url',
            'youtube_url',
            
            // Pixels & Analytics
            'fb_pixel_id',
            'fb_pixel_active',
            'ga_measurement_id',
            'tiktok_pixel_id',

            // Courier APIs
            'steadfast_api_key',
            'steadfast_secret_key',
            'pathao_client_id',
            'pathao_secret_key',
            
            // SMS Gateway
            'sms_api_key',
            'sms_sender_id',
            
            // SMTP & General
            'smtp_host',
            'smtp_port',
            'smtp_user',
            'smtp_pass',
            'store_currency_symbol',
            'order_prefix',
            'hotline_phone',
            'support_email',
            'store_address',
        ];

        foreach ($keys as $k) {
            if ($request->has($k)) {
                ThemeSetting::updateOrCreate(['key' => $k], ['value' => $request->input($k, '')]);
            }
        }

        // Auto format m.me link if username given
        if ($request->filled('facebook_page_username')) {
            $username = ltrim(trim($request->input('facebook_page_username')), '@');
            ThemeSetting::updateOrCreate(['key' => 'facebook_messenger_url'], ['value' => 'https://m.me/' . $username]);
            if (!$request->filled('facebook_url')) {
                ThemeSetting::updateOrCreate(['key' => 'facebook_url'], ['value' => 'https://facebook.com/' . $username]);
            }
        }

        return redirect()->back()->with('success', '⚡ সোশ্যাল মিডিয়া, ফেসবুক পেজ ও স্টোর কনফিগারেশন সফলভাবে সংরক্ষিত হয়েছে!');
    }
}
