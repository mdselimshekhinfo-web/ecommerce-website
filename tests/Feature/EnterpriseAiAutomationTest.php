<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\AutoSeoService;
use App\Services\WhatsAppVerificationService;
use App\Services\VoiceCallingService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EnterpriseAiAutomationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Category::create([
            'id' => 1,
            'name' => 'Cyber Audio',
            'name_bn' => 'সাইবার অডিও',
            'slug' => 'cyber-audio',
            'status' => 'active',
        ]);

        Product::create([
            'id' => 1,
            'category_id' => 1,
            'name' => 'AuraBlade ANC Cyber Earbuds Pro',
            'name_bn' => 'অরাব্লেড এএনসি প্রো',
            'slug' => 'aurablade-anc-cyber-earbuds-pro',
            'sku' => 'AB-ANC-01',
            'price' => 2950,
            'sale_price' => 2950,
            'stock_quantity' => 10,
            'status' => 'active',
        ]);
    }

    public function test_ai_autoseo_generates_meta_tags_and_sitemap()
    {
        $product = Product::first();
        AutoSeoService::generateForProduct($product);

        $product->refresh();
        $this->assertNotEmpty($product->meta_title);
        $this->assertNotEmpty($product->meta_description);
        $this->assertNotEmpty($product->meta_keywords);
        $this->assertGreaterThanOrEqual(85, $product->seo_score);

        // Google JSON-LD schema
        $schema = AutoSeoService::generateJsonLdSchema($product);
        $this->assertEquals('Product', $schema['@type']);
        $this->assertEquals('BDT', $schema['offers']['priceCurrency']);

        // XML Sitemap endpoint
        $sitemapRes = $this->get('/sitemap.xml');
        $sitemapRes->assertStatus(200);
        $sitemapRes->assertHeader('Content-Type', 'application/xml');
        $sitemapRes->assertSee('aurablade-anc-cyber-earbuds-pro');
    }

    public function test_whatsapp_ai_verification_and_auto_courier_booking()
    {
        $order = Order::create([
            'order_number' => 'TEST-WA-01',
            'customer_name' => 'Tanvir Ahmed',
            'customer_phone' => '01711000111',
            'delivery_district' => 'Dhaka',
            'delivery_address' => 'Mirpur 10, Dhaka',
            'shipping_zone' => 'inside_dhaka',
            'shipping_cost' => 60,
            'subtotal' => 2950,
            'total_amount' => 3010,
            'order_status' => 'pending',
            'verification_status' => 'unverified',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => 1,
            'product_name' => 'AuraBlade ANC Cyber Earbuds Pro',
            'price' => 2950,
            'quantity' => 1,
            'total' => 2950,
        ]);

        // WhatsApp message prompt generation
        $waPrompt = WhatsAppVerificationService::generateVerificationMessage($order);
        $this->assertStringContainsString('Tanvir Ahmed', $waPrompt);
        $this->assertStringContainsString('3,010', $waPrompt);

        // Customer replies 'হ্যাঁ' via WhatsApp Webhook simulation
        $result = WhatsAppVerificationService::processIncomingReply('01711000111', 'হ্যাঁ পাঠিয়ে দিন');
        
        $this->assertTrue($result['success']);
        $this->assertEquals('confirmed_and_booked', $result['action']);

        // Verify order status and auto courier assignment in database
        $order->refresh();
        $this->assertEquals('processing', $order->order_status);
        $this->assertEquals('whatsapp_verified', $order->verification_status);
        $this->assertEquals('Steadfast Courier', $order->courier_name);
        $this->assertNotEmpty($order->tracking_code);
    }

    public function test_ai_voice_call_verification_and_auto_dispatch()
    {
        $order = Order::create([
            'order_number' => 'TEST-VOICE-01',
            'customer_name' => 'Sabbir Rahman',
            'customer_phone' => '01899887766',
            'delivery_district' => 'Dhaka',
            'delivery_address' => 'Gulshan 2, Dhaka',
            'shipping_zone' => 'inside_dhaka',
            'shipping_cost' => 60,
            'subtotal' => 2950,
            'total_amount' => 3010,
            'order_status' => 'pending',
            'verification_status' => 'unverified',
        ]);

        // Voice Script Generation
        $script = VoiceCallingService::generateVoiceScript($order);
        $this->assertStringContainsString('Sabbir Rahman', $script['voice_script']);

        // Customer confirms via Voice Call
        $result = VoiceCallingService::processVoiceResponse($order, 'হ্যাঁ আমি অর্ডারটি নিব');
        $this->assertTrue($result['success']);
        $this->assertEquals('confirmed', $result['status']);

        // Verify database update
        $order->refresh();
        $this->assertEquals('processing', $order->order_status);
        $this->assertEquals('voice_call_verified', $order->verification_status);
        $this->assertNotEmpty($order->voice_call_log);
    }

    public function test_admin_ai_automation_hub_endpoints()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Admin opens hub
        $hubRes = $this->actingAs($admin)->get('/admin/ai-automation');
        $hubRes->assertStatus(200);
        $hubRes->assertSee('WhatsApp');
        $hubRes->assertSee('AI');

        // Admin generates SEO
        $seoRes = $this->actingAs($admin)->post('/admin/ai-automation/generate-seo');
        $seoRes->assertStatus(302);
    }
}
