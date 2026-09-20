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
        Schema::create('instant_sells', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->decimal('total_cost', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('vat_tax', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->decimal('total_profit', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('due_amount', 15, 2)->default(0);
            $table->date('due_date')->nullable();
            $table->enum('payment_status', ['paid', 'partial', 'due'])->default('paid');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instant_sells');
    }
};
