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
        if (Schema::hasTable('raw_stock_histories')) {
            Schema::table('raw_stock_histories', function (Blueprint $table) {
                if (!Schema::hasColumn('raw_stock_histories', 'raw_material_purchase_id')) {
                    $table->foreignId('raw_material_purchase_id')
                        ->nullable()
                        ->after('purchase_id')
                        ->constrained('raw_material_purchases')
                        ->onDelete('set null');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('raw_stock_histories')) {
            Schema::table('raw_stock_histories', function (Blueprint $table) {
                if (Schema::hasColumn('raw_stock_histories', 'raw_material_purchase_id')) {
                    $table->dropForeign(['raw_material_purchase_id']);
                    $table->dropColumn('raw_material_purchase_id');
                }
            });
        }
    }
};
