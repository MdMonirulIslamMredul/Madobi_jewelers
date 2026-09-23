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
        Schema::table('karigor_jobs', function (Blueprint $table) {
            $table->boolean('is_raw_material_given')->default(false)->after('assigned_extra_raw_gold');
            $table->foreignId('raw_material_category_id')->nullable()->after('is_raw_material_given')->constrained('product_categories')->onDelete('set null');
            $table->decimal('given_raw_material', 10, 3)->default(0)->after('raw_material_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karigor_jobs', function (Blueprint $table) {
            $table->dropForeign(['raw_material_category_id']);
            $table->dropColumn(['is_raw_material_given', 'raw_material_category_id', 'given_raw_material']);
        });
    }
};
