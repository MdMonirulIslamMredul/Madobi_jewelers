<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('custom_order_items', function (Blueprint $table) {
            $table->decimal('unit_price_per_gram', 15, 2)->default(0)->after('raw_gold_needed');
            $table->decimal('estimated_price', 15, 2)->default(0)->after('unit_price_per_gram');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_order_items', function (Blueprint $table) {
            $table->dropColumn(['unit_price_per_gram', 'estimated_price']);
        });
    }
};
