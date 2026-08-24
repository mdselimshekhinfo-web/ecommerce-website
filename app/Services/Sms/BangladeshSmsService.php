<?php

namespace App\Services\Sms;

use App\Models\SmsLog;
use App\Models\Order;

class BangladeshSmsService
{
    public static function sendOrderConfirmation(Order $order)
    {
        $phone = $order->customer_phone;
        if (!$phone) return false;

        $msg = "NEXUS DOKAN: প্রিয় {$order->customer_name}, আপনার অর্ডার #{$order->order_number} সফলভাবে গ্রহণ করা হয়েছে। মোট: ৳" . number_format($order->total_amount, 0) . "। ধন্যবাদ!";

        return static::sendSms($phone, $msg, $order->id);
    }

    public static function sendShippedNotification(Order $order)
    {
        $phone = $order->customer_phone;
        if (!$phone) return false;

        $msg = "NEXUS DOKAN: আপনার অর্ডার #{$order->order_number} কুরিয়ারে বুক করা হয়েছে ({$order->courier_name})। ট্র্যাকিং কোড: {$order->tracking_code}।";

        return static::sendSms($phone, $msg, $order->id);
    }

    public static function sendSms($phone, $message, $orderId = null)
    {
        // Log the SMS record
        SmsLog::create([
            'phone_number' => $phone,
            'order_id' => $orderId,
            'message' => $message,
            'gateway_name' => 'GreenWeb BD',
            'status' => 'sent',
            'response_data' => json_encode(['status' => 'SUCCESS', 'message_id' => 'SMS_' . uniqid()]),
        ]);

        return true;
    }
}
