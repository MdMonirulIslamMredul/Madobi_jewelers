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
            // Drop foreign key and column if they exist
            if (Schema::hasColumn('product_price', 'product_id')) {
                $table->dropForeign(['product_id']);
                $table->dropColumn('product_id');
            }
            // Add the new product_name column
            $table->string('product_name')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_price', function (Blueprint $table) {
            // Remove product_name
            if (Schema::hasColumn('product_price', 'product_name')) {
                $table->dropColumn('product_name');
            }
            // Re‑add product_id foreign key
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->onDelete('cascade');
        });
    }
};
?>
