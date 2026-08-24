<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlacklistedIp;
use Illuminate\Http\Request;

class AdminBlacklistController extends Controller
{
    public function index()
    {
        $blacklists = BlacklistedIp::latest()->paginate(15);
        $totalBlocked = BlacklistedIp::count();

        return view('admin.fraud.blacklist', compact('blacklists', 'totalBlocked'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ip_address' => 'nullable|string|max:100',
            'phone_number' => 'nullable|string|max:50',
            'reason' => 'required|string|max:255',
        ]);

        if (empty($validated['ip_address']) && empty($validated['phone_number'])) {
            return redirect()->back()->with('error', 'আইপি অ্যাড্রেস অথবা মোবাইল নম্বর যেকোনো একটি প্রদান করতে হবে!');
        }

        BlacklistedIp::create([
            'ip_address' => $validated['ip_address'],
            'phone_number' => $validated['phone_number'],
            'reason' => $validated['reason'],
            'status' => 'blocked',
        ]);

        return redirect()->back()->with('success', 'আইপি / মোবাইল নম্বর সফলভাবে ব্লকলিস্টে যুক্ত হয়েছে! 🛡️');
    }

    public function destroy($id)
    {
        $block = BlacklistedIp::findOrFail($id);
        $block->delete();

        return redirect()->back()->with('success', 'ব্লকলিস্ট থেকে রিমুভ করা হয়েছে!');
    }
}
