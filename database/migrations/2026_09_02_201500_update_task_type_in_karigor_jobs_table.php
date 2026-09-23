<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify task_type column to VARCHAR(100) to support 'custom_order', 'Repair', 'Raw Gold(Paka kora)', etc.
        DB::statement("ALTER TABLE `karigor_jobs` MODIFY `task_type` VARCHAR(100) NOT NULL DEFAULT 'custom_order';");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `karigor_jobs` MODIFY `task_type` ENUM('Repair', 'Raw Gold(Paka kora)') NOT NULL;");
    }
};
