<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Suppliers Table
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->decimal('opening_balance', 12, 2)->default(0.00);
            $table->decimal('total_purchased', 12, 2)->default(0.00);
            $table->decimal('total_paid', 12, 2)->default(0.00);
            $table->decimal('current_due', 12, 2)->default(0.00);
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
        });

        // 2. Purchase Orders Table
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->decimal('subtotal', 12, 2)->default(0.00);
            $table->decimal('shipping_cost', 12, 2)->default(0.00);
            $table->decimal('total_amount', 12, 2)->default(0.00);
            $table->decimal('paid_amount', 12, 2)->default(0.00);
            $table->decimal('due_amount', 12, 2)->default(0.00);
            $table->string('status')->default('ordered'); // draft, ordered, received, cancelled
            $table->string('payment_status')->default('unpaid'); // paid, partial, unpaid
            $table->text('notes')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamps();
        });

        // 3. Purchase Order Items Table
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->string('product_name');
            $table->decimal('unit_cost', 12, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        // 4. Supplier Transactions / Ledger Table
        Schema::create('supplier_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->foreignId('purchase_order_id')->nullable()->constrained('purchase_orders')->onDelete('set null');
            $table->string('type'); // purchase, payment, return
            $table->decimal('amount', 12, 2);
            $table->string('payment_method')->nullable(); // bank, bkash, cash, cheque
            $table->string('reference_no')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('running_balance', 12, 2)->default(0.00);
            $table->timestamps();
        });

        // 5. Abandoned Carts Table
        Schema::create('abandoned_carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('session_id')->index();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->json('items')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0.00);
            $table->string('recovery_status')->default('pending'); // pending, contacted, recovered, lost
            $table->text('recovery_notes')->nullable();
            $table->timestamp('last_active_at')->nullable();
            $table->timestamps();
        });

        // 6. SMS Logs Table
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
            $table->text('message');
            $table->string('gateway_name')->default('GreenWeb');
            $table->string('status')->default('sent'); // sent, failed, simulated
            $table->text('response_data')->nullable();
            $table->timestamps();
        });

        // 7. Add columns to Products
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('cost_price', 12, 2)->nullable()->after('price');
        });

        // 8. Add columns to Orders
        Schema::table('orders', function (Blueprint $table) {
            $table->string('courier_consignment_id')->nullable()->after('tracking_code');
            $table->string('courier_label_url')->nullable()->after('courier_consignment_id');
            $table->string('customer_risk_score')->default('low')->after('payment_status'); // low, medium, high
            $table->string('risk_reason')->nullable()->after('customer_risk_score');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['courier_consignment_id', 'courier_label_url', 'customer_risk_score', 'risk_reason']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['cost_price']);
        });

        Schema::dropIfExists('sms_logs');
        Schema::dropIfExists('abandoned_carts');
        Schema::dropIfExists('supplier_transactions');
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('suppliers');
    }
};
