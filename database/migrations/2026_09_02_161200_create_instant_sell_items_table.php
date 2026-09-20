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
        Schema::create('instant_sell_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instant_sell_id');
            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->string('product_name')->nullable();
            $table->string('category_name')->nullable();
            $table->string('karat')->nullable();
            $table->decimal('bhori', 10, 4)->default(0);
            $table->decimal('ana', 10, 4)->default(0);
            $table->decimal('roti', 10, 4)->default(0);
            $table->decimal('point', 10, 4)->default(0);
            $table->decimal('gram', 10, 4)->default(0);
            $table->decimal('purchase_cost', 15, 2)->default(0);
            $table->decimal('selling_price', 15, 2)->default(0);
            $table->decimal('profit', 15, 2)->default(0);
            $table->timestamps();

            $table->foreign('instant_sell_id')->references('id')->on('instant_sells')->onDelete('cascade');
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instant_sell_items');
    }
};
