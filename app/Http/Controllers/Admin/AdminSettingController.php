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
            'steadfast_api_key',
            'steadfast_secret_key',
            'pathao_client_id',
            'pathao_secret_key',
            'sms_api_key',
            'sms_sender_id',
            'smtp_host',
            'smtp_port',
            'smtp_user',
            'smtp_pass',
            'store_currency_symbol',
            'order_prefix',
        ];

        foreach ($keys as $k) {
            if ($request->has($k)) {
                ThemeSetting::updateOrCreate(['key' => $k], ['value' => $request->input($k, '')]);
            }
        }

        return redirect()->back()->with('success', 'এপিআই ও স্টোর কনফিগারেশন সফলভাবে সেভ হয়েছে! ⚙️');
    }
}
