<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManualOrderAndStockSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_manual_order_and_stock_is_deducted_automatically()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $category = Category::create([
            'name' => 'Audio',
            'slug' => 'audio',
            'status' => 'active',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'AuraBlade ANC Pro Earbuds',
            'slug' => 'aurablade-anc-pro',
            'sku' => 'AB-001',
            'price' => 2950,
            'sale_price' => 2950,
            'stock_quantity' => 10,
            'sales_count' => 0,
            'status' => 'active',
        ]);

        // 1. Admin opens manual order creation form
        $createRes = $this->actingAs($admin)->get('/admin/orders/create');
        $createRes->assertStatus(200);
        $createRes->assertSee('ম্যানুয়াল অর্ডার এন্ট্রি');

        // 2. Admin submits manual order from Facebook Messenger
        $postRes = $this->actingAs($admin)->post('/admin/orders', [
            'customer_name' => 'তানভীর রহমান',
            'customer_phone' => '01712345678',
            'delivery_address' => 'বাড়ি ১২, রোড ৪, ধানমন্ডি, ঢাকা',
            'delivery_district' => 'Dhaka',
            'order_channel' => 'facebook',
            'shipping_cost' => 60,
            'discount_amount' => 50,
            'payment_method' => 'cod',
            'payment_status' => 'unpaid',
            'admin_notes' => 'Confirmed via FB Messenger chat',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ]
            ],
        ]);

        $postRes->assertStatus(302);

        // 3. Verify Order Created in Database
        $this->assertDatabaseCount('orders', 1);
        $order = Order::first();
        $this->assertStringStartsWith('FB-', $order->order_number);
        $this->assertEquals('তানভীর রহমান', $order->customer_name);
        $this->assertEquals('01712345678', $order->customer_phone);
        $this->assertEquals(5900, $order->subtotal); // 2950 * 2
        $this->assertEquals(5910, $order->total_amount); // 5900 + 60 - 50

        // 4. Verify Stock was AUTOMATICALLY DECREMENTED from 10 to 8
        $product->refresh();
        $this->assertEquals(8, $product->stock_quantity);
        $this->assertEquals(2, $product->sales_count);

        // 5. Test Stock Restoration when Order is Cancelled
        $cancelRes = $this->actingAs($admin)->post("/admin/orders/{$order->id}/status", [
            'order_status' => 'cancelled',
            'payment_status' => 'unpaid',
        ]);
        $cancelRes->assertStatus(302);

        $product->refresh();
        $this->assertEquals(10, $product->stock_quantity); // Stock restored back to 10!
    }
}
