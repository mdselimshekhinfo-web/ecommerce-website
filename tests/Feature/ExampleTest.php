<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Database\Seeders\EcommerceSeeder;

class ExampleTest extends TestCase
{
    use DatabaseMigrations;

    public function test_the_application_returns_a_successful_response(): void
    {
        $this->seed(EcommerceSeeder::class);
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
