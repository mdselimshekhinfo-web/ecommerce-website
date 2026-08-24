<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('gateway_type'); // payment, courier, sms, other
            $table->string('gateway_code')->unique(); // bkash, nagad, sslcommerz, steadfast, pathao, bulksmsbd, custom_xxx
            $table->string('display_name');
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_sandbox')->default(false);
            $table->json('credentials')->nullable(); // stored as key-value pairs
            $table->text('instructions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_gateways');
    }
};
