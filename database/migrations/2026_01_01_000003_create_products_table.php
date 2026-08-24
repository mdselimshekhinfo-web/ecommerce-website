<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->text('short_description')->nullable();
            $table->text('short_description_bn')->nullable();
            $table->longText('description')->nullable();
            $table->longText('description_bn')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->integer('stock_quantity')->default(10);
            $table->string('thumbnail')->nullable();
            $table->json('images')->nullable();
            $table->json('variants')->nullable(); // [{"name": "Color", "options": ["Cyber Blue", "Neon Purple", "Stealth Black"]}, {"name": "Storage", "options": ["128GB", "256GB"]}]
            $table->json('specs')->nullable(); // {"Display": "6.7 inch AMOLED 120Hz", "Battery": "5000mAh 65W Fast Charge"}
            $table->string('badge')->nullable(); // "HOT", "NEW", "50% OFF", "AI POWERED"
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_trending')->default(false);
            $table->boolean('is_flash_deal')->default(false);
            $table->timestamp('flash_deal_end')->nullable();
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->integer('reviews_count')->default(0);
            $table->integer('sales_count')->default(0);
            $table->string('status')->default('active'); // active, draft, out_of_stock
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
