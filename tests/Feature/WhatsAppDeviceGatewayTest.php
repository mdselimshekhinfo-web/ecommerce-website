<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Services\WhatsAppDeviceGatewayService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WhatsAppDeviceGatewayTest extends TestCase
{
    use RefreshDatabase;

    public function test_whatsapp_device_gateway_status_and_pairing()
    {
        $status = WhatsAppDeviceGatewayService::getStatus();
        $this->assertArrayHasKey('status', $status);
        $this->assertArrayHasKey('phone', $status);
        $this->assertArrayHasKey('battery', $status);

        $paired = WhatsAppDeviceGatewayService::pairDevice('01947521688');
        $this->assertTrue($paired['success']);
        $this->assertStringContainsString('8801947521688', $paired['phone']);

        $sent = WhatsAppDeviceGatewayService::sendMessage('01947521688', 'Test Message');
        $this->assertTrue($sent['success']);
        $this->assertEquals('+8801947521688', $sent['recipient']);
    }

    public function test_admin_can_pair_and_send_test_whatsapp_message()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $resPair = $this->actingAs($admin)->postJson('/admin/ai-automation/pair-whatsapp', [
            'phone' => '01947521688',
        ]);
        $resPair->assertStatus(200);
        $resPair->assertJson(['success' => true]);

        $resSend = $this->actingAs($admin)->postJson('/admin/ai-automation/send-test-whatsapp', [
            'phone' => '01947521688',
            'message' => 'Hello Test',
        ]);
        $resSend->assertStatus(200);
        $resSend->assertJson(['success' => true]);
    }
}
