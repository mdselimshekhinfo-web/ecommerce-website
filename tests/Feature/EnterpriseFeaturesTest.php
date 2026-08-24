<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use App\Models\AbandonedCart;
use App\Models\Order;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Database\Seeders\EcommerceSeeder;
use Database\Seeders\EnterpriseSeeder;
use App\Helpers\ThemeDefaults;

use Database\Seeders\MarketingSeeder;
use Database\Seeders\GatewaySeeder;

class EnterpriseFeaturesTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(EcommerceSeeder::class);
        $this->seed(EnterpriseSeeder::class);
        $this->seed(MarketingSeeder::class);
        $this->seed(GatewaySeeder::class);
        ThemeDefaults::seedDefaults();
    }

    public function test_supplier_creation_and_ledger_transaction(): void
    {
        $admin = User::where('email', 'admin@nexusdokan.bd')->first();
        $this->actingAs($admin);

        $response = $this->post('/admin/suppliers', [
            'name' => 'Mohammad Ali',
            'company_name' => 'Ali Electronics BD',
            'phone' => '01711998877',
            'email' => 'ali@alielectronics.com',
            'address' => 'Motijheel, Dhaka',
            'opening_balance' => 15000,
        ]);

        $response->assertRedirect('/admin/suppliers');
        $supplier = Supplier::where('phone', '01711998877')->first();
        $this->assertNotNull($supplier);
        $this->assertEquals(15000, $supplier->opening_balance);

        // Record a Payment
        $payResponse = $this->post("/admin/suppliers/{$supplier->id}/payment", [
            'amount' => 5000,
            'payment_method' => 'bank',
            'reference_no' => 'CHQ-9812',
            'notes' => 'Partial payment',
        ]);

        $payResponse->assertRedirect();
        $supplier->refresh();
        $this->assertEquals(10000, $supplier->current_due);
    }

    public function test_purchase_order_creation_and_stock_auto_replenishment(): void
    {
        $admin = User::where('email', 'admin@nexusdokan.bd')->first();
        $this->actingAs($admin);

        $supplier = Supplier::first();
        $product = Product::first();
        $initialStock = $product->stock_quantity;

        // Create PO with status 'received'
        $response = $this->post('/admin/purchase-orders', [
            'supplier_id' => $supplier->id,
            'status' => 'received',
            'shipping_cost' => 200,
            'paid_amount' => 10000,
            'items' => [
                [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_cost' => 1500,
                    'quantity' => 20,
                ]
            ]
        ]);

        $product->refresh();
        $this->assertEquals($initialStock + 20, $product->stock_quantity);
        $this->assertEquals(1500, $product->cost_price);
    }

    public function test_courier_booking_and_shipping_label(): void
    {
        $admin = User::where('email', 'admin@nexusdokan.bd')->first();
        $this->actingAs($admin);

        $order = Order::first();
        $response = $this->post("/admin/orders/{$order->id}/book-courier");

        $response->assertRedirect();
        $order->refresh();
        $this->assertNotNull($order->courier_consignment_id);
        $this->assertEquals('shipped', $order->order_status);

        $labelResponse = $this->get("/admin/orders/{$order->id}/courier-label");
        $labelResponse->assertStatus(200);
        $labelResponse->assertSee($order->order_number);
    }

    public function test_pnl_analytics_and_profit_calculation(): void
    {
        $admin = User::where('email', 'admin@nexusdokan.bd')->first();
        $this->actingAs($admin);

        $response = $this->get('/admin/analytics/pnl');
        $response->assertStatus(200);
        $response->assertSee('NET PROFIT');
    }

    public function test_abandoned_cart_recovery_sms(): void
    {
        $admin = User::where('email', 'admin@nexusdokan.bd')->first();
        $this->actingAs($admin);

        $cart = AbandonedCart::first();
        $response = $this->post("/admin/abandoned-carts/{$cart->id}/sms");

        $response->assertRedirect();
        $cart->refresh();
        $this->assertEquals('contacted', $cart->recovery_status);
    }

    public function test_pixel_settings_update(): void
    {
        $admin = User::where('email', 'admin@nexusdokan.bd')->first();
        $this->actingAs($admin);

        $response = $this->post('/admin/marketing/pixels/update', [
            'fb_pixel_id' => '998877665544',
            'gtm_id' => 'GTM-TEST1234',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('theme_settings', [
            'key' => 'fb_pixel_id',
            'value' => '998877665544',
        ]);
    }

    public function test_landing_page_direct_order_flow(): void
    {
        $landingPage = \App\Models\LandingPage::first();
        $this->assertNotNull($landingPage);

        $response = $this->get('/landing/' . $landingPage->slug);
        $response->assertStatus(200);
        $response->assertSee($landingPage->offer_price);

        // Place direct COD order
        $orderResponse = $this->post('/landing/' . $landingPage->slug . '/order', [
            'customer_name' => 'Farhan Kabir',
            'customer_phone' => '01711002233',
            'delivery_district' => 'Dhaka',
            'delivery_address' => 'House 14, Road 2, Dhanmondi',
            'quantity' => 1,
            'variant' => 'Cyber Dark Edition',
        ]);

        $orderResponse->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'customer_phone' => '01711002233',
            'delivery_district' => 'Dhaka',
        ]);
    }

    public function test_custom_policy_page_display(): void
    {
        $page = \App\Models\CustomPage::where('slug', 'return-refund-policy')->first();
        $this->assertNotNull($page);

        $response = $this->get('/page/return-refund-policy');
        $response->assertStatus(200);
        $response->assertSee('৭ দিনের সহজ রিটার্ন পলিসি');
    }

    public function test_staff_creation_and_listing(): void
    {
        $admin = User::where('email', 'admin@nexusdokan.bd')->first();
        $this->actingAs($admin);

        $response = $this->post('/admin/staff', [
            'name' => 'Kaiser Ahmed',
            'email' => 'kaiser@nexusdokan.bd',
            'phone' => '01799887766',
            'role' => 'manager',
            'password' => 'secret123',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'email' => 'kaiser@nexusdokan.bd',
            'role' => 'manager',
        ]);

        $listResponse = $this->get('/admin/staff');
        $listResponse->assertStatus(200);
        $listResponse->assertSee('Kaiser Ahmed');
    }

    public function test_customer_review_submission_from_product_page(): void
    {
        $product = \App\Models\Product::first();
        $this->assertNotNull($product);

        $response = $this->post("/product/{$product->slug}/review", [
            'reviewer_name' => 'Sadia Islam',
            'reviewer_phone' => '01711223344',
            'rating' => 5,
            'comment' => 'অসাধারণ সাউন্ড কোয়ালিটি এবং ফাস্ট ডেলিভারি!',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('product_reviews', [
            'reviewer_name' => 'Sadia Islam',
            'product_id' => $product->id,
            'rating' => 5,
        ]);
    }

    public function test_customer_address_book(): void
    {
        $user = User::where('role', 'customer')->first();
        $this->actingAs($user);

        $response = $this->post('/customer/addresses', [
            'label' => 'Office',
            'name' => $user->name,
            'phone' => '01711223344',
            'district' => 'Dhaka',
            'address' => 'Floor 8, Tower 71, Mohakhali C/A',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('user_addresses', [
            'user_id' => $user->id,
            'label' => 'Office',
            'district' => 'Dhaka',
        ]);
    }

    public function test_single_gateway_update(): void
    {
        $admin = User::where('email', 'admin@nexusdokan.bd')->first();
        $this->actingAs($admin);

        $gateway = \App\Models\CustomGateway::where('gateway_code', 'bkash_pgw')->first();
        $this->assertNotNull($gateway);

        $response = $this->put("/admin/gateways/{$gateway->id}", [
            'display_name' => 'bKash Live Merchant PGW',
            'is_active' => '1',
            'is_sandbox' => '0',
            'credentials' => [
                'app_key' => 'live_app_key_123',
                'app_secret' => 'live_secret_456',
            ]
        ]);

        $response->assertRedirect();
        $gateway->refresh();
        $this->assertEquals('bKash Live Merchant PGW', $gateway->display_name);
        $this->assertEquals('live_app_key_123', $gateway->getCredential('app_key'));
    }

    public function test_gateway_toggle_status(): void
    {
        $admin = User::where('email', 'admin@nexusdokan.bd')->first();
        $this->actingAs($admin);

        $gateway = \App\Models\CustomGateway::first();
        $initialStatus = $gateway->is_active;

        $response = $this->post("/admin/gateways/{$gateway->id}/toggle");
        $response->assertRedirect();

        $gateway->refresh();
        $this->assertEquals(!$initialStatus, $gateway->is_active);
    }

    public function test_add_new_custom_gateway(): void
    {
        $admin = User::where('email', 'admin@nexusdokan.bd')->first();
        $this->actingAs($admin);

        $response = $this->post('/admin/gateways', [
            'gateway_type' => 'payment',
            'display_name' => 'Upay Direct Merchant Gateway',
            'credentials_keys' => ['merchant_id', 'api_token'],
            'credentials_values' => ['UPAY_8819', 'tok_upay_9921'],
            'instructions' => 'Upay developer API configuration',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('custom_gateways', [
            'display_name' => 'Upay Direct Merchant Gateway',
            'gateway_type' => 'payment',
        ]);
    }

    public function test_admin_language_switch_between_bengali_and_english(): void
    {
        $admin = User::where('email', 'admin@nexusdokan.bd')->first();
        $this->actingAs($admin);

        // Switch to English
        $responseEn = $this->get('/language/en');
        $responseEn->assertRedirect();
        $this->assertEquals('en', session('app_locale'));

        // View dashboard in English
        $dashResponseEn = $this->get('/admin');
        $dashResponseEn->assertStatus(200);
        $dashResponseEn->assertSee('Gross Revenue');

        // Toggle to Bengali
        $responseToggle = $this->get('/language/toggle');
        $responseToggle->assertRedirect();
        $this->assertEquals('bn', session('app_locale'));

        // View dashboard in Bengali
        $dashResponseBn = $this->get('/admin');
        $dashResponseBn->assertStatus(200);
        $dashResponseBn->assertSee('আজকের মোট সেলস');

        // View Storefront in Bengali
        $homeBn = $this->get('/');
        $homeBn->assertStatus(200);
        $homeBn->assertSee('সকল প্রোডাক্ট');
    }
}
