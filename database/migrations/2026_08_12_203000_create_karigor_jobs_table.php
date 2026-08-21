<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKarigorJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karigor_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases')->onDelete('cascade');
            $table->foreignId('karigor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');

            $table->enum('task_type', ['Repair', 'Raw Gold(Paka kora)']);
            $table->enum('status', ['in_progress', 'completed', 'cancelled'])->default('in_progress');

            // Given Input Data
            $table->decimal('given_gross_weight', 10, 3)->nullable();
            $table->decimal('given_purity_weight', 10, 3)->nullable();
            $table->decimal('assigned_extra_raw_gold', 10, 3)->default(0);

            // Returned Output Data
            $table->decimal('returned_gross_weight', 10, 3)->nullable();
            $table->decimal('returned_raw_gold', 10, 3)->nullable();
            $table->decimal('used_extra_raw_gold', 10, 3)->default(0);
            $table->decimal('wastage_gold', 10, 3)->nullable();
            $table->decimal('conversion_percentage', 5, 2)->nullable();

            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('karigor_jobs');
    }
}
