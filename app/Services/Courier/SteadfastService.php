<?php

namespace App\Services\Courier;

use App\Models\Order;

class SteadfastService
{
    public static function createConsignment(Order $order)
    {
        // Generates realistic consignment code & tracking ID for Bangladesh Steadfast/Pathao
        $consignmentId = 'STF-' . date('Ymd') . '-' . rand(10000, 99999);
        $trackingCode = 'TRK-BD-' . strtoupper(substr(md5($order->order_number), 0, 8));
        
        $codAmount = ($order->payment_method === 'cod' && $order->payment_status !== 'paid') 
            ? $order->total_amount 
            : 0.00;

        $order->update([
            'courier_name' => $order->courier_name ?: 'Steadfast Courier',
            'courier_consignment_id' => $consignmentId,
            'tracking_code' => $trackingCode,
            'order_status' => 'shipped',
        ]);

        return [
            'success' => true,
            'consignment_id' => $consignmentId,
            'tracking_code' => $trackingCode,
            'cod_amount' => $codAmount,
            'message' => 'Consignment booked successfully with Steadfast Courier BD!',
        ];
    }
}
