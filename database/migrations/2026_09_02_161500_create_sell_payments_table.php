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
        Schema::create('sell_payments', function (Blueprint $table) {
            $table->id();
            $table->enum('payment_type', ['instant_sell', 'custom_order']);
            $table->unsignedBigInteger('instant_sell_id')->nullable();
            $table->unsignedBigInteger('custom_order_id')->nullable();
            $table->integer('payment_step')->default(1);
            $table->decimal('amount', 15, 2);
            $table->string('payment_method')->default('cash'); // cash, bank, bkash, nagad, card
            $table->string('transaction_reference')->nullable();
            $table->dateTime('payment_date');
            $table->unsignedBigInteger('received_by')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();

            $table->foreign('instant_sell_id')->references('id')->on('instant_sells')->onDelete('cascade');
            $table->foreign('custom_order_id')->references('id')->on('custom_orders')->onDelete('cascade');
            $table->foreign('received_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sell_payments');
    }
};
