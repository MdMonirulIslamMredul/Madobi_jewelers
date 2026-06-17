<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('product_suppliers')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('product_categories')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('karat');
            $table->string('unit_price');
            $table->string('qtr')->nullable();
            $table->string('bhori')->nullable();
            $table->string('ana')->nullable();
            $table->string('roti')->nullable();
            $table->string('total_price');
            $table->string('adv_payment');
            $table->string('due_payment');
            $table->string('total_payment');
            $table->date('order_date');
            $table->date('receive_date');
            $table->boolean('is_shop')->default(false);
            $table->boolean('is_warehouse')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
