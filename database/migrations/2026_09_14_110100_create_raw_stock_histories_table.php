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
        if (!Schema::hasTable('raw_stock_histories')) {
            Schema::create('raw_stock_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('raw_stock_id')->constrained('raw_stocks')->onDelete('cascade');
                $table->foreignId('category_id')->constrained('product_categories')->onDelete('cascade');
                $table->foreignId('karigor_job_id')->nullable()->constrained('karigor_jobs')->onDelete('set null');
                $table->foreignId('custom_order_id')->nullable()->constrained('custom_orders')->onDelete('set null');
                $table->foreignId('purchase_id')->nullable()->constrained('purchases')->onDelete('set null');
                $table->foreignId('karigor_id')->nullable()->constrained('users')->onDelete('set null');
                
                $table->enum('type', ['in', 'out', 'adjustment'])->default('in');
                $table->decimal('gram', 12, 3)->default(0);
                $table->integer('bhori')->default(0);
                $table->integer('ana')->default(0);
                $table->integer('roti')->default(0);
                $table->integer('point')->default(0);
                $table->decimal('carat', 10, 3)->default(0);

                $table->decimal('previous_gram', 12, 3)->default(0);
                $table->decimal('current_gram', 12, 3)->default(0);

                $table->string('reason')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_stock_histories');
    }
};
