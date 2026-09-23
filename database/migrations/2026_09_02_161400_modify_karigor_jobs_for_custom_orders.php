<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('karigor_jobs', function (Blueprint $table) {
            $table->unsignedBigInteger('custom_order_id')->nullable()->after('purchase_id');
            $table->foreign('custom_order_id')->references('id')->on('custom_orders')->onDelete('cascade');
        });

        // Make purchase_id nullable using raw SQL to ensure reliability across all DB drivers
        DB::statement('ALTER TABLE `karigor_jobs` MODIFY `purchase_id` BIGINT UNSIGNED NULL;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karigor_jobs', function (Blueprint $table) {
            $table->dropForeign(['custom_order_id']);
            $table->dropColumn('custom_order_id');
        });

        DB::statement('ALTER TABLE `karigor_jobs` MODIFY `purchase_id` BIGINT UNSIGNED NOT NULL;');
    }
};
