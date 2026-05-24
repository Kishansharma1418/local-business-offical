<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            if (!Schema::hasColumn('tenants', 'background_color')) {
                $table->string('background_color')->default('#ffffff')->after('primary_color');
            }
            if (!Schema::hasColumn('tenants', 'text_color')) {
                $table->string('text_color')->default('#111418')->after('background_color');
            }
            if (!Schema::hasColumn('tenants', 'accent_color')) {
                $table->string('accent_color')->nullable()->after('text_color');
            }
            if (!Schema::hasColumn('tenants', 'website_mode')) {
                // 'shop' = full e-commerce with products & cart
                // 'simple' = info-only website (about, contact, enquiry), no cart/catalog
                $table->enum('website_mode', ['shop', 'simple'])->default('shop')->after('theme');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['background_color', 'text_color', 'accent_color', 'website_mode']);
        });
    }
};
