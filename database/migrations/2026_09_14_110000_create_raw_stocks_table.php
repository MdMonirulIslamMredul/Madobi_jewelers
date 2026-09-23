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
        if (!Schema::hasTable('raw_stocks')) {
            Schema::create('raw_stocks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->unique()->constrained('product_categories')->onDelete('cascade');
                $table->string('material_name');
                $table->decimal('gram', 12, 3)->default(0);
                $table->integer('bhori')->default(0);
                $table->integer('ana')->default(0);
                $table->integer('roti')->default(0);
                $table->integer('point')->default(0);
                $table->decimal('carat', 10, 3)->default(0);
                $table->decimal('cost_per_gram', 12, 2)->nullable();
                $table->decimal('total_cost', 15, 2)->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });

            // Initialize default stock rows for existing categories
            $categories = [
                ['id' => 1, 'name' => 'Raw Gold (খাঁটি সোনা)'],
                ['id' => 2, 'name' => 'Raw Rupa (খাঁটি রূপা)'],
                ['id' => 3, 'name' => 'Diamond (হীরা)'],
                ['id' => 4, 'name' => 'Platinum (প্লাটিনাম)'],
            ];

            foreach ($categories as $cat) {
                $catExists = DB::table('product_categories')->where('id', $cat['id'])->exists();
                if ($catExists) {
                    DB::table('raw_stocks')->insert([
                        'category_id'   => $cat['id'],
                        'material_name' => $cat['name'],
                        'gram'          => 0.000,
                        'bhori'         => 0,
                        'ana'           => 0,
                        'roti'          => 0,
                        'point'         => 0,
                        'carat'         => 0.000,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_stocks');
    }
};
