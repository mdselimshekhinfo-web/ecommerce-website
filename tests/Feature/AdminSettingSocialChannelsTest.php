<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\ThemeSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminSettingSocialChannelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_facebook_page_and_social_settings()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/admin/settings/update', [
            'facebook_page_username' => 'nexusdokanbd',
            'instagram_url' => 'https://instagram.com/nexusdokanbd',
            'tiktok_url' => 'https://tiktok.com/@nexusdokanbd',
            'youtube_url' => 'https://youtube.com/@nexusdokanbd',
            'whatsapp_number' => '+8801947521688',
            'fb_pixel_id' => '1849204829104',
        ]);

        $response->assertStatus(302);
        $this->assertEquals('https://m.me/nexusdokanbd', ThemeSetting::get('facebook_messenger_url'));
        $this->assertEquals('https://facebook.com/nexusdokanbd', ThemeSetting::get('facebook_url'));
        $this->assertEquals('1849204829104', ThemeSetting::get('fb_pixel_id'));
    }
}
