<?php

namespace App\Services;

use App\Models\Order;

class AiFraudScoreService
{
    /**
     * Compute AI Fraud & Trust Risk Score for an Order
     */
    public static function analyzeOrder(Order $order): array
    {
        $score = 85; // Baseline healthy score
        $reasons = [];

        // 1. Phone number structure check
        $cleanPhone = preg_replace('/[^0-9]/', '', $order->customer_phone);
        if (strlen($cleanPhone) >= 11 && (str_starts_with($cleanPhone, '01') || str_starts_with($cleanPhone, '8801'))) {
            $score += 5;
            $reasons[] = "✓ বৈধ বাংলাদেশি মোবাইল নম্বর ফরম্যাট (+5%)";
        } else {
            $score -= 25;
            $reasons[] = "⚠ সন্দেহজনক বা অসম্পূর্ণ মোবাইল নম্বর (-25%)";
        }

        // 2. Address completeness check
        if (strlen($order->delivery_address) > 15) {
            $score += 10;
            $reasons[] = "✓ বিস্তারিত ডেলিভারি ঠিকানা বিদ্যমান (+10%)";
        } else {
            $score -= 15;
            $reasons[] = "⚠ সংক্ষিপ্ত বা অস্পষ্ট ডেলিভারি ঠিকানা (-15%)";
        }

        // 3. Past customer order history analysis
        $pastOrders = Order::where('customer_phone', 'like', "%" . substr($cleanPhone, -10) . "%")
            ->where('id', '!=', $order->id)
            ->get();

        if ($pastOrders->count() > 0) {
            $cancelledCount = $pastOrders->where('order_status', 'cancelled')->count();
            $deliveredCount = $pastOrders->where('order_status', 'delivered')->count();

            if ($deliveredCount > 0) {
                $score += 15;
                $reasons[] = "✓ পূর্বে {$deliveredCount}টি সফল ডেলিভারি রেকর্ড রয়েছে (+15%)";
            }

            if ($cancelledCount > 1) {
                $score -= 30;
                $reasons[] = "🚨 পূর্বে {$cancelledCount}টি অর্ডার বাতিলের রেকর্ড রয়েছে (-30%)";
            }
        }

        // 4. High-Value COD Risk Check
        if ($order->total_amount > 10000 && $order->payment_status !== 'paid') {
            $score -= 10;
            $reasons[] = "ℹ️ ১০,০০০ টাকার বেশি উচ্চমূল্যের ক্যাশ-অন-ডেলিভারি অর্ডার (-10%)";
        }

        // Bound score between 5 and 99
        $score = max(5, min(99, $score));

        if ($score >= 80) {
            $level = 'safe';
            $badgeColor = 'emerald';
            $recommendation = "নিরাপদ ও বিশ্বস্ত অর্ডার — সরাসরি কুরিয়ারে বুকিং করুন।";
        } elseif ($score >= 55) {
            $level = 'moderate';
            $badgeColor = 'amber';
            $recommendation = "মাঝারি ঝুঁকি — ফোনে কল করে অথবা হোয়াটসঅ্যাপে কনফার্ম করে নিন।";
        } else {
            $level = 'high_risk';
            $badgeColor = 'red';
            $recommendation = "উচ্চ ঝুঁকি / ফ্রড অ্যালার্ট — কুরিয়ারে পাঠানোর পূর্বে অগ্রিম ডেলিভারি চার্জ গ্রহণ করুন!";
        }

        return [
            'score' => $score,
            'level' => $level,
            'badge_color' => $badgeColor,
            'recommendation' => $recommendation,
            'reasons' => $reasons,
        ];
    }
}
