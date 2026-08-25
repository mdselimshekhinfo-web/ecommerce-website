<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Services\AiMarketingCopyService;
use App\Services\AiFraudScoreService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EnterpriseAiMarketingAndFraudTest extends TestCase
{
    use RefreshDatabase;

    public function test_ai_marketing_copy_service_generates_ad_copies()
    {
        $category = Category::create([
            'name' => 'Smart Gadgets',
            'slug' => 'smart-gadgets',
            'status' => 'active',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'AuraBlade ANC Cyber Earbuds Pro',
            'slug' => 'aurablade-anc-cyber-earbuds-pro',
            'sku' => 'AB-AI-01',
            'price' => 3500,
            'sale_price' => 2950,
            'stock_quantity' => 25,
            'status' => 'active',
        ]);

        $copy = AiMarketingCopyService::generateAdCopy($product);

        $this->assertArrayHasKey('facebook_ad_copy', $copy);
        $this->assertArrayHasKey('instagram_caption', $copy);
        $this->assertArrayHasKey('sms_marketing_copy', $copy);
        $this->assertStringContainsString('AuraBlade ANC Cyber Earbuds Pro', $copy['facebook_ad_copy']);
        $this->assertStringContainsString('2,950', $copy['facebook_ad_copy']);
        $this->assertStringContainsString('#NexusDokan', $copy['instagram_caption']);
    }

    public function test_ai_fraud_scoring_service_computes_score_and_recommendation()
    {
        $order = Order::create([
            'order_number' => 'ORD-AI-101',
            'customer_name' => 'তানভীর আহমেদ',
            'customer_phone' => '01947521688',
            'customer_email' => 'tanvir@example.com',
            'delivery_address' => 'বাড়ি #১২, রোড #৫, ধানমন্ডি, ঢাকা',
            'delivery_district' => 'Dhaka',
            'subtotal' => 2950,
            'shipping_cost' => 60,
            'total_amount' => 3010,
            'order_status' => 'pending',
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'verification_status' => 'unverified',
        ]);

        $analysis = AiFraudScoreService::analyzeOrder($order);

        $this->assertArrayHasKey('score', $analysis);
        $this->assertArrayHasKey('level', $analysis);
        $this->assertArrayHasKey('recommendation', $analysis);
        $this->assertArrayHasKey('reasons', $analysis);
        $this->assertGreaterThanOrEqual(80, $analysis['score']);
        $this->assertEquals('safe', $analysis['level']);
    }

    public function test_admin_can_call_ai_marketing_copy_endpoint()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $category = Category::create(['name' => 'Audio', 'slug' => 'audio', 'status' => 'active']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Cyber ANC Pro',
            'slug' => 'cyber-anc-pro',
            'sku' => 'CYBER-01',
            'price' => 2500,
            'sale_price' => 2500,
            'stock_quantity' => 10,
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->postJson('/admin/ai-automation/generate-marketing-copy', [
            'product_id' => $product->id,
            'tone' => 'sales_boost',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'facebook_ad_copy',
            'instagram_caption',
            'sms_marketing_copy',
            'product_name',
        ]);
    }
}
