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
        Schema::table('product_price', function (Blueprint $table) {
            $table->decimal('buying_price_per_gram', 15, 2)->nullable()->after('buying_price');
            $table->decimal('selling_price_per_gram', 15, 2)->nullable()->after('selling_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_price', function (Blueprint $table) {
            $table->dropColumn(['buying_price_per_gram', 'selling_price_per_gram']);
        });
    }
};
