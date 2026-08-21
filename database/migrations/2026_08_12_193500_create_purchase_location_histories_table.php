<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseLocationHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_location_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases')->onDelete('cascade');
            $table->string('from_location')->nullable();
            $table->string('to_location');
            $table->foreignId('transferred_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('assigned_karigor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('task_type')->nullable();
            $table->decimal('extra_raw_gold', 10, 3)->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('purchase_location_histories');
    }
}
