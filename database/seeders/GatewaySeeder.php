<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomGateway;

class GatewaySeeder extends Seeder
{
    public function run(): void
    {
        $gateways = [
            // 1. Payment Gateways
            [
                'gateway_type' => 'payment',
                'gateway_code' => 'bkash_pgw',
                'display_name' => 'bKash Direct Merchant PGW',
                'icon' => 'smartphone',
                'is_active' => true,
                'is_sandbox' => true,
                'credentials' => [
                    'app_key' => 'bkash_app_key_demo_9921',
                    'app_secret' => 'bkash_app_secret_demo_xxyyzz',
                    'username' => '01711000111',
                    'password' => 'pass_demo_bkash',
                    'merchant_number' => '01711000111',
                ],
                'instructions' => 'bKash Merchant Portal থেকে App Key ও Secret Key সংগ্রহ করুন।',
            ],
            [
                'gateway_type' => 'payment',
                'gateway_code' => 'nagad_api',
                'display_name' => 'Nagad Merchant API',
                'icon' => 'credit-card',
                'is_active' => true,
                'is_sandbox' => true,
                'credentials' => [
                    'merchant_id' => 'NAGAD_M_68819',
                    'public_key' => 'nagad_public_key_demo',
                    'private_key' => 'nagad_private_key_demo',
                    'merchant_number' => '01811000222',
                ],
                'instructions' => 'Nagad Developer Portal থেকে মার্চেন্ট ক্রেডেনশিয়াল বসান।',
            ],
            [
                'gateway_type' => 'payment',
                'gateway_code' => 'sslcommerz',
                'display_name' => 'SSLCommerz / Shurjopay (Cards & MFS)',
                'icon' => 'globe',
                'is_active' => false,
                'is_sandbox' => true,
                'credentials' => [
                    'store_id' => 'nexus_dokan_live',
                    'store_password' => 'store_pass_9981',
                ],
                'instructions' => 'ভিসা, মাস্টারকার্ড এবং কার্ড পেমেন্টের জন্য SSLCommerz Store ID দিন।',
            ],
            [
                'gateway_type' => 'payment',
                'gateway_code' => 'cod',
                'display_name' => 'Cash on Delivery (ক্যাশ অন ডেলিভারি)',
                'icon' => 'banknote',
                'is_active' => true,
                'is_sandbox' => false,
                'credentials' => [
                    'min_order_amount' => '0',
                    'max_order_amount' => '100000',
                    'advance_fee' => '0',
                ],
                'instructions' => 'পণ্য হাতে পেয়ে মূল্য পরিশোধের সুবিধা।',
            ],

            // 2. Courier Gateways
            [
                'gateway_type' => 'courier',
                'gateway_code' => 'steadfast',
                'display_name' => 'Steadfast Courier API',
                'icon' => 'truck',
                'is_active' => true,
                'is_sandbox' => true,
                'credentials' => [
                    'api_key' => 'st_demo_key_994821',
                    'secret_key' => 'st_sec_8849201948',
                    'base_url' => 'https://portal.steadfast.com.bd/api/v1',
                ],
                'instructions' => 'Steadfast মার্চেন্ট প্যানেলের Settings > API থেকে Key সংগ্রহ করুন।',
            ],
            [
                'gateway_type' => 'courier',
                'gateway_code' => 'pathao',
                'display_name' => 'Pathao Courier Logistics API',
                'icon' => 'navigation',
                'is_active' => true,
                'is_sandbox' => true,
                'credentials' => [
                    'client_id' => 'pathao_cid_9921',
                    'client_secret' => 'pathao_sec_8831',
                    'username' => 'merchant@nexusdokan.bd',
                    'password' => 'pathao_pass',
                    'store_id' => '10492',
                ],
                'instructions' => 'Pathao Developer Console থেকে API ক্রেডেনশিয়াল বসান।',
            ],
            [
                'gateway_type' => 'courier',
                'gateway_code' => 'redx',
                'display_name' => 'RedX Express Logistics',
                'icon' => 'box',
                'is_active' => false,
                'is_sandbox' => true,
                'credentials' => [
                    'api_token' => 'redx_token_991823',
                ],
                'instructions' => 'RedX মার্চেন্ট টোকেন বসান।',
            ],

            // 3. SMS Gateways
            [
                'gateway_type' => 'sms',
                'gateway_code' => 'bulksmsbd',
                'display_name' => 'BulkSMSBD / Greenweb API',
                'icon' => 'message-square',
                'is_active' => true,
                'is_sandbox' => true,
                'credentials' => [
                    'api_token' => 'sms_token_demo_992101',
                    'sender_id' => 'NEXUS DOKAN',
                ],
                'instructions' => 'অটোমেটিক অর্ডার কনফার্মেশন ও ওটিপি বাংলা এসএমএস প্রেরক।',
            ],
        ];

        foreach ($gateways as $gw) {
            CustomGateway::updateOrCreate(
                ['gateway_code' => $gw['gateway_code']],
                $gw
            );
        }
    }
}
