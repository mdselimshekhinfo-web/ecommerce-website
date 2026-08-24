<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'verification_status')) {
                $table->string('verification_status')->default('unverified')->after('order_status'); // unverified, whatsapp_verified, voice_call_verified, rejected
            }
            if (!Schema::hasColumn('orders', 'voice_call_log')) {
                $table->text('voice_call_log')->nullable()->after('verification_status');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('badge');
            }
            if (!Schema::hasColumn('products', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('products', 'meta_keywords')) {
                $table->string('meta_keywords')->nullable()->after('meta_description');
            }
            if (!Schema::hasColumn('products', 'seo_score')) {
                $table->integer('seo_score')->default(85)->after('meta_keywords');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['verification_status', 'voice_call_log']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description', 'meta_keywords', 'seo_score']);
        });
    }
};
