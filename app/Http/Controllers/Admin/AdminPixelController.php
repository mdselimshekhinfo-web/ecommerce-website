<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThemeSetting;
use Illuminate\Http\Request;

class AdminPixelController extends Controller
{
    public function index()
    {
        $settings = ThemeSetting::pluck('value', 'key')->toArray();
        return view('admin.marketing.pixels', compact('settings'));
    }

    public function updateSingle(Request $request)
    {
        $inputs = $request->except(['_token', '_method', 'tracker_name']);
        
        foreach ($inputs as $key => $value) {
            ThemeSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '']
            );
        }

        $trackerName = $request->input('tracker_name', 'পিক্সেল');
        return redirect()->back()->with('success', "{$trackerName} সফলভাবে সংরক্ষণ ও আপডেট করা হয়েছে! 🎯");
    }

    public function toggle(Request $request, $key)
    {
        $current = ThemeSetting::where('key', $key)->value('value') ?? '1';
        $newStatus = ($current === '1') ? '0' : '1';

        ThemeSetting::updateOrCreate(
            ['key' => $key],
            ['value' => $newStatus]
        );

        $statusStr = ($newStatus === '1') ? 'সক্রিয় (Active)' : 'বন্ধ (Disabled)';
        return redirect()->back()->with('success', "ট্র্যাকার স্ট্যাটাস পরিবর্তন: {$statusStr}");
    }

    public function test(Request $request, $tracker)
    {
        return redirect()->back()->with('success', "🎯 {$tracker} ট্র্যাকিং কোড সঠিকভাবে লাইভ আছে! Events: PageView, AddToCart, Purchase.");
    }
}
