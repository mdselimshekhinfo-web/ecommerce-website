<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone');
            $table->string('delivery_district');
            $table->text('delivery_address');
            $table->text('delivery_notes')->nullable();
            $table->string('shipping_zone')->default('inside_dhaka'); // inside_dhaka, outside_dhaka
            $table->decimal('shipping_cost', 10, 2)->default(60.00);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0.00);
            $table->string('coupon_code')->nullable();
            $table->decimal('total_amount', 12, 2);
            $table->string('payment_method')->default('cod'); // cod, bkash, nagad, rocket, card
            $table->string('payment_status')->default('pending'); // pending, paid, failed, refunded
            $table->string('transaction_id')->nullable();
            $table->string('order_status')->default('pending'); // pending, processing, packed, shipped, delivered, cancelled
            $table->string('courier_name')->nullable(); // Steadfast, Pathao, RedX, Paperfly
            $table->string('tracking_code')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
