<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\SupplierTransaction;
use App\Models\AbandonedCart;
use App\Models\Product;
use App\Models\Order;
use App\Models\SmsLog;

class EnterpriseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Update Products with Cost Prices (কেনা দাম)
        $costPrices = [
            'aurablade-anc-cyber-earbuds-pro' => 1750.00,
            'chronos-x-neural-smartwatch' => 3800.00,
            'vortex-75-cyber-mechanical-keyboard' => 3200.00,
            'quantum-pulse-8k-wireless-mouse' => 1900.00,
            'mechacharge-130w-gan-cyber-powerbank' => 2400.00,
            'synthwave-rgb-smart-soundbar' => 2100.00,
            'holopack-modular-cyberpunk-backpack' => 1800.00,
            'nexus-titan-wireless-charging-stand' => 850.00,
        ];

        foreach ($costPrices as $slug => $cost) {
            Product::where('slug', $slug)->update(['cost_price' => $cost]);
        }

        // 2. Demo Suppliers in Bangladesh
        $supplier1 = Supplier::create([
            'name' => 'Khandaker Rafiqul Islam',
            'company_name' => 'CyberTech Importers BD',
            'phone' => '01711223344',
            'email' => 'rafiq@cybertechbd.com',
            'address' => 'Shop #402, Multiplan Center, Elephant Road, Dhaka-1205',
            'opening_balance' => 0.00,
            'total_purchased' => 125000.00,
            'total_paid' => 100000.00,
            'current_due' => 25000.00,
            'status' => 'active',
        ]);

        $supplier2 = Supplier::create([
            'name' => 'Shahnewaz Chowdhury',
            'company_name' => 'Nexus Global Peripherals Hub',
            'phone' => '01819887766',
            'email' => 'shahnewaz@nexusglobal.com',
            'address' => 'Level 5, IDB Bhaban, Agargaon, Dhaka-1207',
            'opening_balance' => 5000.00,
            'total_purchased' => 85000.00,
            'total_paid' => 70000.00,
            'current_due' => 20000.00,
            'status' => 'active',
        ]);

        // 3. Purchase Orders
        $po1 = PurchaseOrder::create([
            'po_number' => 'PO-2026-0001',
            'supplier_id' => $supplier1->id,
            'subtotal' => 87500.00,
            'shipping_cost' => 500.00,
            'total_amount' => 88000.00,
            'paid_amount' => 88000.00,
            'due_amount' => 0.00,
            'status' => 'received',
            'payment_status' => 'paid',
            'notes' => 'Bulk shipment received at Gulshan Central Hub.',
            'received_at' => now()->subDays(10),
        ]);

        $p1 = Product::where('slug', 'aurablade-anc-cyber-earbuds-pro')->first();
        if ($p1) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $po1->id,
                'product_id' => $p1->id,
                'product_name' => $p1->name,
                'unit_cost' => 1750.00,
                'quantity' => 50,
                'subtotal' => 87500.00,
            ]);
        }

        // Transactions / Ledger for Supplier 1
        SupplierTransaction::create([
            'supplier_id' => $supplier1->id,
            'purchase_order_id' => $po1->id,
            'type' => 'purchase',
            'amount' => 88000.00,
            'notes' => 'Invoice for PO-2026-0001',
            'running_balance' => 88000.00,
            'created_at' => now()->subDays(10),
        ]);

        SupplierTransaction::create([
            'supplier_id' => $supplier1->id,
            'purchase_order_id' => $po1->id,
            'type' => 'payment',
            'amount' => 88000.00,
            'payment_method' => 'bank',
            'reference_no' => 'EBL-TRX-948123',
            'notes' => 'Full payment via Eastern Bank Corporate Transfer',
            'running_balance' => 0.00,
            'created_at' => now()->subDays(9),
        ]);

        // 4. Abandoned Carts
        AbandonedCart::create([
            'session_id' => 'sess_demo_9841',
            'customer_name' => 'Kamrul Hasan',
            'customer_phone' => '01712998877',
            'customer_email' => 'kamrul.dev@gmail.com',
            'items' => [
                [
                    'name' => 'Vortex 75% Cyber Mechanical Keyboard',
                    'price' => 4850,
                    'quantity' => 1,
                    'variant' => 'Cyber Dark Edition / Gateron Yellow',
                    'thumbnail' => 'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?w=600&auto=format&fit=crop&q=80',
                ]
            ],
            'subtotal' => 4850.00,
            'recovery_status' => 'pending',
            'recovery_notes' => 'Stopped at bKash payment step',
            'last_active_at' => now()->subHours(2),
        ]);

        AbandonedCart::create([
            'session_id' => 'sess_demo_1042',
            'customer_name' => 'Ayesha Siddiqua',
            'customer_phone' => '01911445566',
            'customer_email' => 'ayesha.ctg@yahoo.com',
            'items' => [
                [
                    'name' => 'Chronos-X Neural Smartwatch',
                    'price' => 5400,
                    'quantity' => 1,
                    'variant' => 'Titanium Silver / Silicone Orange',
                    'thumbnail' => 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=600&auto=format&fit=crop&q=80',
                ]
            ],
            'subtotal' => 5400.00,
            'recovery_status' => 'pending',
            'recovery_notes' => 'Added to cart from Chattogram',
            'last_active_at' => now()->subHours(5),
        ]);

        // 5. SMS Logs
        SmsLog::create([
            'phone_number' => '01811223344',
            'message' => 'NEXUS DOKAN: আপনার অর্ডার #NX-2026-9812 সফলভাবে গৃহীত হয়েছে। মোট: ৳২,৯৫০। ট্র্যাকিং কোড: TRK-981240',
            'gateway_name' => 'GreenWeb BD',
            'status' => 'sent',
            'created_at' => now()->subDay(),
        ]);
    }
}
