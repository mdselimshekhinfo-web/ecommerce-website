<?php

namespace App\Http\Controllers;

use App\Models\LuckySpin;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LuckyWheelController extends Controller
{
    public function spin(Request $request)
    {
        $ip = $request->ip();
        $userId = Auth::id();

        // Segments on wheel: index 0 to 5
        $prizes = [
            0 => ['label' => '৳100 Discount', 'code' => 'NEXUS200', 'type' => 'discount', 'desc' => 'Instant Discount on Min ৳1500'],
            1 => ['label' => '10% OFF Voucher', 'code' => 'CYBER10', 'type' => 'discount', 'desc' => '10% off on all cyber products'],
            2 => ['label' => 'Free Delivery BD', 'code' => 'FREESHIPBD', 'type' => 'shipping', 'desc' => 'Free delivery anywhere in Bangladesh'],
            3 => ['label' => '৳500 Mega Cyber Prize', 'code' => 'SPIN500', 'type' => 'mega', 'desc' => '৳500 voucher on orders above ৳3000'],
            4 => ['label' => '15% Off VIP Secret', 'code' => 'CYBER10', 'type' => 'discount', 'desc' => 'VIP Discount code unlocked!'],
            5 => ['label' => '৳200 Flat Cash Discount', 'code' => 'NEXUS200', 'type' => 'discount', 'desc' => '৳200 Off for your next checkout'],
        ];

        // Random prize index
        $wonIndex = array_rand($prizes);
        $wonPrize = $prizes[$wonIndex];

        LuckySpin::create([
            'ip_address' => $ip,
            'user_id' => $userId,
            'prize_won' => $wonPrize['label'],
            'coupon_code' => $wonPrize['code'],
        ]);

        return response()->json([
            'success' => true,
            'segment_index' => $wonIndex,
            'prize' => $wonPrize['label'],
            'code' => $wonPrize['code'],
            'description' => $wonPrize['desc'],
            'message' => "🎉 Congratulations! You won {$wonPrize['label']}! Use coupon code: {$wonPrize['code']}",
        ]);
    }
}
