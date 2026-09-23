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
        Schema::create('karigor_mojuris', function (Blueprint $table) {
            $table->id();
            $table->string('category_name'); // gold, Rupa, diamond, Platinum
            $table->string('type');          // 22k, 21k, 18k
            $table->decimal('per_vori_tk', 15, 2)->default(0);
            $table->decimal('per_gram_tk', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karigor_mojuris');
    }
};
