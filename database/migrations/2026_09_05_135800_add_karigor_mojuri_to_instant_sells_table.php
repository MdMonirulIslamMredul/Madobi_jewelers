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
        Schema::table('instant_sells', function (Blueprint $table) {
            $table->unsignedBigInteger('karigor_mojuri_id')->nullable()->after('subtotal');
            $table->decimal('karigor_mojuri_rate', 15, 2)->default(0)->after('karigor_mojuri_id');
            $table->decimal('karigor_mojuri_total', 15, 2)->default(0)->after('karigor_mojuri_rate');

            $table->foreign('karigor_mojuri_id')->references('id')->on('karigor_mojuris')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instant_sells', function (Blueprint $table) {
            $table->dropForeign(['karigor_mojuri_id']);
            $table->dropColumn(['karigor_mojuri_id', 'karigor_mojuri_rate', 'karigor_mojuri_total']);
        });
    }
};
