<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique()->index();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('current_page')->nullable();
            $table->json('cart_summary')->nullable();
            $table->boolean('is_assigned_to_human')->default(false);
            $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['active', 'closed', 'auto_pilot'])->default('auto_pilot');
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index();
            $table->enum('sender_type', ['customer', 'ai', 'agent'])->default('customer');
            $table->string('sender_name')->default('Customer');
            $table->text('message');
            $table->string('message_type')->default('text'); // text, order_receipt, product_card, whatsapp_redirect
            $table->json('payload')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_sessions');
    }
};
