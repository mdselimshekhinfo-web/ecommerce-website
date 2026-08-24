<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use App\Models\ThemeSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LiveChatAndAutoPilotTest extends TestCase
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

    public function test_customer_can_initialize_live_chat_session()
    {
        $response = $this->postJson('/api/live-chat/init', [
            'current_page' => 'http://127.0.0.1:8000/shop',
            'cart_summary' => [],
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'session_id', 'is_assigned_to_human', 'messages']);
        $this->assertDatabaseCount('chat_sessions', 1);
        $this->assertDatabaseCount('chat_messages', 1);
    }

    public function test_customer_message_triggers_ai_autopilot_response()
    {
        $initRes = $this->postJson('/api/live-chat/init');
        $initRes->assertStatus(200);

        $response = $this->postJson('/api/live-chat/send', [
            'message' => 'ডেলিভারি চার্জ কত?',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'user_message', 'reply']);
        $this->assertDatabaseCount('chat_messages', 3); // Greeting + User msg + AI reply
    }

    public function test_ai_autopilot_automatically_creates_and_books_order()
    {
        $this->postJson('/api/live-chat/init');

        $response = $this->postJson('/api/live-chat/send', [
            'message' => 'আমি AuraBlade Earbuds নিতে চাই। নাম: তানভীর, ফোন: 01712345678, ঠিকানা: মিরপুর ১০, ঢাকা',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('reply.message_type', 'order_receipt');

        // Verify Order was created in database
        $this->assertDatabaseCount('orders', 1);
        $order = Order::first();
        $this->assertEquals('01712345678', $order->customer_phone);
        $this->assertEquals('pending', $order->order_status);
        $this->assertEquals('🤖 Booked via AI Auto-Pilot Sales Agent', $order->admin_notes);
    }

    public function test_admin_support_desk_and_agent_messaging()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $session = ChatSession::create([
            'session_id' => 'SES-TEST1234',
            'customer_name' => 'Rahim',
            'customer_phone' => '01811223344',
            'status' => 'active',
            'last_activity_at' => now(),
        ]);

        ChatMessage::create([
            'session_id' => 'SES-TEST1234',
            'sender_type' => 'customer',
            'sender_name' => 'Rahim',
            'message' => 'Hello support team',
            'message_type' => 'text',
        ]);

        // Admin opens support desk
        $deskRes = $this->actingAs($admin)->get('/admin/live-chat');
        $deskRes->assertStatus(200);
        $deskRes->assertSee('LIVE SUPPORT DESK');

        // Admin replies to customer
        $replyRes = $this->actingAs($admin)->postJson('/admin/live-chat/SES-TEST1234/send', [
            'message' => 'Hello Rahim! How can I help you today?',
        ]);

        $replyRes->assertStatus(200);
        $this->assertDatabaseHas('chat_messages', [
            'session_id' => 'SES-TEST1234',
            'sender_type' => 'agent',
            'message' => 'Hello Rahim! How can I help you today?',
        ]);

        // Toggle AutoPilot
        $toggleRes = $this->actingAs($admin)->post('/admin/live-chat/toggle-autopilot');
        $toggleRes->assertStatus(302);
    }
}
