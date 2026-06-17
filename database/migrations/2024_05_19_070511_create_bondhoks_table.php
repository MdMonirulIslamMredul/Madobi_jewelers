<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBondhoksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bondhoks', function (Blueprint $table) {
            $table->id();
            $table->string('product_type_id')->nullable();
            $table->string('karat')->nullable();
            $table->string('bhori')->nullable();
            $table->string('ana')->nullable();
            $table->string('roti')->nullable();
            $table->string('unit_price')->nullable();
            $table->string('qty')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('base_amount')->nullable();
            $table->string('interest_rate')->nullable();
            $table->string('payable_amount')->nullable();
            $table->string('paid')->nullable();
            $table->string('due')->nullable();
            $table->date('start_time')->nullable();
            $table->date('end_time')->nullable();
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
        Schema::dropIfExists('bondhoks');
    }
}
