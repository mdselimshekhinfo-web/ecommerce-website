<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsLog;
use App\Services\Sms\BangladeshSmsService;
use Illuminate\Http\Request;

class AdminSmsController extends Controller
{
    public function index()
    {
        $logs = SmsLog::with('order')->latest()->paginate(15);
        $totalSent = SmsLog::where('status', 'sent')->count();

        return view('admin.sms.index', compact('logs', 'totalSent'));
    }

    public function sendCustom(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        BangladeshSmsService::sendSms($validated['phone'], $validated['message']);

        return redirect()->back()->with('success', 'এসএমএস সফলভাবে কাস্টমারের নম্বরে পাঠানো হয়েছে! 📱');
    }
}
