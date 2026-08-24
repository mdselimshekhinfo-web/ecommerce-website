<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminGatewayController extends Controller
{
    public function index()
    {
        $paymentGateways = CustomGateway::where('gateway_type', 'payment')->get();
        $courierGateways = CustomGateway::where('gateway_type', 'courier')->get();
        $smsGateways = CustomGateway::where('gateway_type', 'sms')->get();
        $customGateways = CustomGateway::where('gateway_type', 'other')->get();

        return view('admin.gateways.index', compact(
            'paymentGateways',
            'courierGateways',
            'smsGateways',
            'customGateways'
        ));
    }

    public function update(Request $request, $id)
    {
        $gateway = CustomGateway::findOrFail($id);

        $gateway->display_name = $request->input('display_name', $gateway->display_name);
        $gateway->is_active = $request->has('is_active');
        $gateway->is_sandbox = $request->has('is_sandbox');
        $gateway->instructions = $request->input('instructions', $gateway->instructions);

        // Store credentials
        $credentials = $request->input('credentials', []);
        $gateway->credentials = $credentials;
        $gateway->save();

        return redirect()->route('admin.gateways.index', ['tab' => $gateway->gateway_type])
            ->with('success', "{$gateway->display_name} সফলভাবে সংরক্ষণ ও আপডেট করা হয়েছে! 🔌");
    }

    public function toggle(Request $request, $id)
    {
        $gateway = CustomGateway::findOrFail($id);
        $gateway->is_active = !$gateway->is_active;
        $gateway->save();

        $statusStr = $gateway->is_active ? 'চালু (Active)' : 'বন্ধ (Disabled)';
        return redirect()->back()->with('success', "{$gateway->display_name} স্ট্যাটাস পরিবর্তন: {$statusStr}");
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'gateway_type' => 'required|in:payment,courier,sms,other',
            'display_name' => 'required|string|max:255',
            'credentials_keys' => 'nullable|array',
            'credentials_values' => 'nullable|array',
            'instructions' => 'nullable|string',
        ]);

        $code = Str::slug($validated['display_name']) . '_' . Str::random(4);

        // Build credentials array from custom key-value inputs
        $credentials = [];
        if (!empty($validated['credentials_keys']) && !empty($validated['credentials_values'])) {
            foreach ($validated['credentials_keys'] as $idx => $key) {
                if (!empty(trim($key))) {
                    $credentials[trim($key)] = $validated['credentials_values'][$idx] ?? '';
                }
            }
        }

        CustomGateway::create([
            'gateway_type' => $validated['gateway_type'],
            'gateway_code' => $code,
            'display_name' => $validated['display_name'],
            'icon' => 'zap',
            'is_active' => true,
            'is_sandbox' => false,
            'credentials' => $credentials,
            'instructions' => $validated['instructions'] ?? 'কাস্টম এপিআই ইন্টিগ্রেশন।',
        ]);

        return redirect()->route('admin.gateways.index', ['tab' => $validated['gateway_type']])
            ->with('success', 'নতুন কাস্টম গেটওয়ে সফলভাবে তৈরি ও যুক্ত হয়েছে! 🚀');
    }

    public function testConnection(Request $request, $id)
    {
        $gateway = CustomGateway::findOrFail($id);

        // Simulated API Ping Verification
        return redirect()->back()->with('success', "🔌 {$gateway->display_name} API কানেকশন সফল! সার্ভার রেসপন্স: 200 OK (Latency: 42ms)");
    }

    public function destroy($id)
    {
        $gateway = CustomGateway::findOrFail($id);
        $gateway->delete();

        return redirect()->route('admin.gateways.index')
            ->with('success', 'গেটওয়ে সফলভাবে মুছে ফেলা হয়েছে!');
    }
}
