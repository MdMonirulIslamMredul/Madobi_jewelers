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
        Schema::create('custom_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('custom_order_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name');
            $table->string('karat')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('target_bhori', 10, 4)->default(0);
            $table->decimal('target_ana', 10, 4)->default(0);
            $table->decimal('target_roti', 10, 4)->default(0);
            $table->decimal('target_point', 10, 4)->default(0);
            $table->decimal('target_gram', 10, 4)->default(0);
            $table->decimal('raw_gold_needed', 10, 4)->default(0);
            $table->unsignedBigInteger('assigned_karigor_id')->nullable();
            $table->unsignedBigInteger('karigor_job_id')->nullable();
            $table->decimal('karigor_fee', 15, 2)->default(0);
            $table->decimal('actual_weight_gram', 10, 4)->nullable();
            $table->decimal('actual_price', 15, 2)->nullable();
            $table->enum('status', ['pending', 'in_production', 'ready_for_delivery', 'delivered', 'cancelled'])->default('in_production');
            $table->string('design_photo')->nullable();
            $table->text('details')->nullable();
            $table->timestamps();

            $table->foreign('custom_order_id')->references('id')->on('custom_orders')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('set null');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('assigned_karigor_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_order_items');
    }
};
