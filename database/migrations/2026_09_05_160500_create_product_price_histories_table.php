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
        Schema::create('product_price_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_price_id')->nullable()->constrained('product_price')->nullOnDelete();
            $table->string('product_name');
            $table->decimal('buying_price', 15, 2)->nullable();
            $table->decimal('buying_price_per_gram', 15, 2)->nullable();
            $table->decimal('selling_price', 15, 2)->nullable();
            $table->decimal('selling_price_per_gram', 15, 2)->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->string('change_type', 50)->default('update'); // 'create', 'update'
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('effective_date')->nullable();
            $table->string('note', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_price_histories');
    }
};
