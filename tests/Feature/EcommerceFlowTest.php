<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Database\Seeders\EcommerceSeeder;

class EcommerceFlowTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(EcommerceSeeder::class);
    }

    public function test_home_page_renders_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('NEXUS');
    }

    public function test_shop_page_renders_with_products(): void
    {
        $response = $this->get('/shop');
        $response->assertStatus(200);
        $response->assertSee('CYBER CATALOG');
    }

    public function test_product_detail_page_renders(): void
    {
        $product = Product::first();
        $response = $this->get('/product/' . $product->slug);
        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    public function test_cart_add_and_view(): void
    {
        $product = Product::first();
        
        $response = $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
            'variant' => 'Cyber Neon',
        ]);

        $response->assertRedirect();
        
        $cartResponse = $this->get('/cart');
        $cartResponse->assertStatus(200);
        $cartResponse->assertSee($product->name);
    }

    public function test_lucky_wheel_spin_api(): void
    {
        $response = $this->postJson('/lucky-wheel/spin');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'segment_index',
            'prize',
            'code',
            'message'
        ]);
    }

    public function test_ai_shopping_assistant_response(): void
    {
        $response = $this->postJson('/ai-assistant/ask', [
            'message' => 'What is the delivery charge in Dhaka?'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['reply']);
        $this->assertStringContainsString('60', $response->json('reply'));
    }

    public function test_full_checkout_and_order_tracking_flow(): void
    {
        $product = Product::first();

        // 1. Add to cart
        $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        // 2. Checkout
        $checkoutResponse = $this->post('/checkout/process', [
            'customer_name' => 'Fahim BD',
            'customer_phone' => '01899112233',
            'customer_email' => 'fahim@bd.com',
            'delivery_district' => 'Dhaka',
            'delivery_address' => 'House 10, Road 5, Dhanmondi, Dhaka',
            'payment_method' => 'bkash',
            'bkash_trx_id' => 'BKS12345678',
        ]);

        $order = Order::latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals('Fahim BD', $order->customer_name);
        $this->assertEquals('inside_dhaka', $order->shipping_zone);
        $this->assertEquals(60.00, $order->shipping_cost);

        $checkoutResponse->assertRedirect(route('order.success', $order->order_number));

        // 3. Track Order
        $trackResponse = $this->get('/track-order?order_number=' . $order->order_number);
        $trackResponse->assertStatus(200);
        $trackResponse->assertSee($order->order_number);

        // 4. Printable Invoice
        $invoiceResponse = $this->get('/order/invoice/' . $order->order_number);
        $invoiceResponse->assertStatus(200);
        $invoiceResponse->assertSee($order->order_number);
    }

    public function test_admin_dashboard_access_protection(): void
    {
        // Unauthenticated access should redirect to login
        $guestResponse = $this->get('/admin');
        $guestResponse->assertRedirect('/login');

        // Admin login and access
        $admin = User::where('email', 'admin@nexusdokan.bd')->first();
        $this->actingAs($admin);

        $adminResponse = $this->get('/admin');
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('NEXUS DOKAN');
    }
}
