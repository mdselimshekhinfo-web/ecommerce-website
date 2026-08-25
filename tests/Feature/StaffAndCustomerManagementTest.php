<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StaffAndCustomerManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_staff_with_granular_permissions()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // 1. Admin creates a manager with specific permissions
        $response = $this->actingAs($admin)->post('/admin/staff', [
            'name' => 'কামরুল হাসান',
            'email' => 'kamrul@example.com',
            'phone' => '01911223344',
            'role' => 'manager',
            'password' => 'password123',
            'permissions' => ['orders', 'products'],
        ]);

        $response->assertStatus(302);

        $staff = User::where('email', 'kamrul@example.com')->first();
        $this->assertNotNull($staff);
        $this->assertEquals('manager', $staff->role);
        $this->assertEquals('active', $staff->status);
        $this->assertTrue($staff->hasPermission('orders'));
        $this->assertTrue($staff->hasPermission('products'));
        $this->assertFalse($staff->hasPermission('analytics'));

        // 2. Admin updates staff status to blocked
        $updateRes = $this->actingAs($admin)->put("/admin/staff/{$staff->id}", [
            'name' => 'কামরুল হাসান',
            'email' => 'kamrul@example.com',
            'phone' => '01911223344',
            'role' => 'staff',
            'status' => 'blocked',
            'permissions' => ['orders'],
        ]);

        $updateRes->assertStatus(302);
        $staff->refresh();
        $this->assertEquals('blocked', $staff->status);
        $this->assertTrue($staff->isBlocked());
    }

    public function test_admin_can_create_customer_and_toggle_status()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // 1. Admin creates a new customer profile
        $createRes = $this->actingAs($admin)->post('/admin/customers', [
            'name' => 'সাদিয়া ইসলাম',
            'email' => 'sadia@example.com',
            'phone' => '01899887766',
            'district' => 'Chattogram',
            'address' => 'আগ্রাবাদ সি/এ, চট্টগ্রাম',
            'password' => 'secret123',
        ]);

        $createRes->assertStatus(302);

        $customer = User::where('email', 'sadia@example.com')->first();
        $this->assertNotNull($customer);
        $this->assertEquals('customer', $customer->role);
        $this->assertEquals('active', $customer->status);
        $this->assertTrue($customer->isActive());

        // 2. Admin toggles status to blocked
        $toggleRes = $this->actingAs($admin)->post("/admin/customers/{$customer->id}/toggle-status");
        $toggleRes->assertStatus(302);

        $customer->refresh();
        $this->assertEquals('blocked', $customer->status);
        $this->assertTrue($customer->isBlocked());
    }

    public function test_product_page_renders_urgency_alert_and_social_share_buttons()
    {
        $category = Category::create([
            'name' => 'Audio',
            'slug' => 'audio',
            'status' => 'active',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'AuraBlade Cyber Earbuds',
            'slug' => 'aurablade-cyber-earbuds',
            'sku' => 'AB-LOW-01',
            'price' => 2950,
            'sale_price' => 2950,
            'stock_quantity' => 3, // Low Stock <= 5
            'status' => 'active',
        ]);

        $response = $this->get("/product/{$product->slug}");
        $response->assertStatus(200);
        $response->assertSee('সীমিত স্টক');
        $response->assertSee('Facebook');
        $response->assertSee('WhatsApp');
    }
}
