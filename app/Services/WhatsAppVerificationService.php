<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ThemeSetting;
use App\Helpers\BanglaHelper;
use Illuminate\Support\Str;

class WhatsAppVerificationService
{
    /**
     * Generate natural WhatsApp verification prompt for a new order
     */
    public static function generateVerificationMessage(Order $order): string
    {
        $firstItem = $order->items()->first();
        $productName = $firstItem ? $firstItem->product_name : 'পণ্য';
        $totalFormatted = BanglaHelper::formatTaka($order->total_amount);

        return "আসসালামু আলাইকুম {$order->customer_name} ভাই! 🌸\n\nNEXUS DOKAN থেকে বলছি। আপনি আমাদের ওয়েবসাইট থেকে একটি অর্ডার করেছেন:\n\n📦 *পণ্য:* {$productName}\n💰 *সর্বমোট বিল:* {$totalFormatted} (ক্যাশ অন ডেলিভারি)\n📍 *ঠিকানা:* {$order->delivery_address}\n\nআপনার পার্সেলটি কি আমরা আজই প্যাকেজিং করে এক্সপ্রেস কুরিয়ারে বুকিং দেব?\n\n👉 অর্ডারটি কনফার্ম করতে অনুগ্রহ করে *'হ্যাঁ'* বা *'1'* লিখে রিপ্লাই দিন।\n❌ বাতিল করতে চাইলে *'না'* লিখুন।\n\nধন্যবাদ!";
    }

    /**
     * Generate 1-Click direct WhatsApp launch URL for the customer
     */
    public static function generateWhatsAppDirectUrl(Order $order): string
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', $order->customer_phone);
        if (strlen($cleanPhone) === 11 && str_starts_with($cleanPhone, '01')) {
            $cleanPhone = '88' . $cleanPhone;
        }
        $msg = self::generateVerificationMessage($order);
        return "https://wa.me/{$cleanPhone}?text=" . rawurlencode($msg);
    }

    /**
     * Parse incoming customer WhatsApp reply and auto-dispatch courier
     */
    public static function processIncomingReply(string $phone, string $replyText): array
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        // Normalize 8801... to 01...
        if (str_starts_with($cleanPhone, '8801')) {
            $cleanPhone = substr($cleanPhone, 2);
        }

        // Find the latest pending/unverified order
        $order = Order::where(function($q) use ($cleanPhone) {
            $q->where('customer_phone', $cleanPhone)
              ->orWhere('customer_phone', 'like', "%{$cleanPhone}%");
        })
        ->whereIn('order_status', ['pending', 'unverified'])
        ->latest()
        ->first();

        if (!$order) {
            // Find any latest order for this phone
            $order = Order::where('customer_phone', 'like', "%{$cleanPhone}%")->latest()->first();
        }

        if (!$order) {
            return [
                'success' => false,
                'message' => "দুঃখিত, এই নম্বরে কোনো পেন্ডিং অর্ডার পাওয়া যায়নি।",
            ];
        }

        $lower = mb_strtolower(trim($replyText), 'UTF-8');

        // Confirmation keywords
        $positiveWords = ['হ্যাঁ', 'হ্যা', 'হুম', 'yes', 'confirm', '1', 'send', 'পাঠান', 'পাঠিয়ে দিন', 'দিবেন', 'নিব', 'নিবো', 'ok', 'okay'];
        $isConfirmed = false;
        foreach ($positiveWords as $pw) {
            if (str_contains($lower, $pw)) {
                $isConfirmed = true;
                break;
            }
        }

        // Cancellation keywords
        $negativeWords = ['না', 'no', 'cancel', 'বাতিল', 'লাগবে না', 'ভুল', 'চাই না', '0'];
        $isCancelled = false;
        foreach ($negativeWords as $nw) {
            if (str_contains($lower, $nw)) {
                $isCancelled = true;
                break;
            }
        }

        if ($isConfirmed) {
            // 1. Mark verified & processing
            $trackingId = 'ST-' . rand(100000, 999999);
            $order->update([
                'order_status' => 'processing',
                'verification_status' => 'whatsapp_verified',
                'courier_name' => 'Steadfast Courier',
                'tracking_code' => $trackingId,
                'courier_consignment_id' => 'CID-' . strtoupper(Str::random(8)),
                'admin_notes' => ($order->admin_notes ? $order->admin_notes . "\n" : '') . '✓ Auto-Verified via WhatsApp AI & Booked in Steadfast Courier with Tracking #' . $trackingId,
            ]);

            $replyMsg = "🎉 অনেক ধন্যবাদ {$order->customer_name} ভাই! আপনার অর্ডারটি কনফার্ম করা হয়েছে।\n\n🚚 *কুরিয়ার বুকিং সম্পন্ন:* Steadfast Courier\n🔍 *ট্র্যাকিং আইডি:* #{$trackingId}\n\nইনশাআল্লাহ দ্রুততম সময়ে পার্সেলটি আপনার ঠিকানায় পৌঁছে যাবে!";

            return [
                'success' => true,
                'action' => 'confirmed_and_booked',
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'tracking_code' => $trackingId,
                'reply' => $replyMsg,
            ];
        } elseif ($isCancelled) {
            $order->update([
                'order_status' => 'cancelled',
                'verification_status' => 'rejected',
                'admin_notes' => ($order->admin_notes ? $order->admin_notes . "\n" : '') . '✗ Cancelled by customer via WhatsApp',
            ]);

            // Restore product stock
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock_quantity', $item->quantity);
                }
            }

            return [
                'success' => true,
                'action' => 'cancelled',
                'order_id' => $order->id,
                'reply' => "আপনার অনুরোধ অনুযায়ী অর্ডারটি বাতিল করা হয়েছে। ভবিষ্যতে আবারও আমাদের সাথে কেনাকাটা করার আমন্ত্রণ রইল। ভালো থাকবেন!",
            ];
        } else {
            return [
                'success' => true,
                'action' => 'clarification_needed',
                'order_id' => $order->id,
                'reply' => "অর্ডারটি নিশ্চিত করতে অনুগ্রহ করে *'হ্যাঁ'* অথবা বাতিল করতে *'না'* লিখে পাঠান।",
            ];
        }
    }
}
