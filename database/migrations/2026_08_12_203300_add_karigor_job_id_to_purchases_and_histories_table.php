<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKarigorJobIdToPurchasesAndHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->foreignId('karigor_job_id')->nullable()->after('location')->constrained('karigor_jobs')->onDelete('set null');
        });

        Schema::table('purchase_location_histories', function (Blueprint $table) {
            $table->foreignId('karigor_job_id')->nullable()->after('purchase_id')->constrained('karigor_jobs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign(['karigor_job_id']);
            $table->dropColumn('karigor_job_id');
        });

        Schema::table('purchase_location_histories', function (Blueprint $table) {
            $table->dropForeign(['karigor_job_id']);
            $table->dropColumn('karigor_job_id');
        });
    }
}
