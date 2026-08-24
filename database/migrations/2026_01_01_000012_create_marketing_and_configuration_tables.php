<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Landing Pages Table
        Schema::create('landing_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->string('headline')->nullable();
            $table->text('subheadline')->nullable();
            $table->decimal('offer_price', 12, 2);
            $table->decimal('regular_price', 12, 2)->nullable();
            $table->string('video_url')->nullable();
            $table->string('banner_image')->nullable();
            $table->json('features_list')->nullable();
            $table->string('countdown_end_time')->nullable();
            $table->text('custom_css')->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
        });

        // 2. Product Reviews Table
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('reviewer_name');
            $table->string('reviewer_phone')->nullable();
            $table->integer('rating')->default(5); // 1 to 5
            $table->text('comment');
            $table->json('images')->nullable();
            $table->boolean('is_verified_purchase')->default(true);
            $table->string('status')->default('approved'); // approved, pending, rejected
            $table->timestamps();
        });

        // 3. Custom Policy & Content Pages
        Schema::create('custom_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('is_footer_link')->default(true);
            $table->string('status')->default('published'); // published, draft
            $table->timestamps();
        });

        // 4. Blacklisted IPs and Fraud Phone Numbers
        Schema::create('blacklisted_ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address')->nullable()->index();
            $table->string('phone_number')->nullable()->index();
            $table->string('reason')->nullable();
            $table->string('status')->default('blocked'); // blocked, warning
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blacklisted_ips');
        Schema::dropIfExists('custom_pages');
        Schema::dropIfExists('product_reviews');
        Schema::dropIfExists('landing_pages');
    }
};
