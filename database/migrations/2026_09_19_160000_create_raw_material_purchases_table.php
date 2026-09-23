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
        if (!Schema::hasTable('raw_material_purchases')) {
            Schema::create('raw_material_purchases', function (Blueprint $table) {
                $table->id();
                $table->foreignId('raw_stock_id')->nullable()->constrained('raw_stocks')->onDelete('set null');
                $table->foreignId('category_id')->constrained('product_categories')->onDelete('cascade');
                $table->string('invoice_no')->unique()->index();
                $table->string('supplier_name')->nullable();
                $table->string('supplier_phone')->nullable();
                $table->date('purchase_date');
                $table->string('material_name')->nullable();
                $table->string('karat')->nullable(); // e.g. 24K, 22K, 21K, 18K, Traditional
                $table->decimal('gram', 12, 3)->default(0);
                $table->integer('bhori')->default(0);
                $table->integer('ana')->default(0);
                $table->integer('roti')->default(0);
                $table->integer('point')->default(0);
                $table->decimal('carat', 10, 3)->default(0); // for diamond
                $table->decimal('unit_price', 12, 2)->nullable();
                $table->enum('rate_type', ['per_gram', 'per_bhori'])->default('per_gram');
                $table->decimal('total_amount', 15, 2)->default(0);
                $table->decimal('paid_amount', 15, 2)->default(0);
                $table->decimal('due_amount', 15, 2)->default(0);
                $table->string('payment_method')->nullable()->default('Cash');
                $table->text('notes')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_material_purchases');
    }
};
