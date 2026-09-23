<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawStock extends Model
{
    use HasFactory;

    protected $table = 'raw_stocks';

    protected $fillable = [
        'category_id',
        'material_name',
        'gram',
        'bhori',
        'ana',
        'roti',
        'point',
        'carat',
        'cost_per_gram',
        'total_cost',
        'notes',
    ];

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }

    public function histories()
    {
        return $this->hasMany(RawStockHistory::class, 'raw_stock_id', 'id')->latest();
    }

    public function rawMaterialPurchases()
    {
        return $this->hasMany(RawMaterialPurchase::class, 'raw_stock_id', 'id')->latest();
    }

    /**
     * Convert grams to traditional Bengali jewelry units (ভরি, আনা, রতি, পয়েন্ট).
     * 1 ভরি = 11.664 গ্রাম = 16 আনা = 96 রতি = 960 পয়েন্ট
     * 1 পয়েন্ট = 0.01215 গ্রাম
     */
    public static function convertGramToUnits($grams)
    {
        $grams = floatval($grams);
        if ($grams <= 0) {
            return ['bhori' => 0, 'ana' => 0, 'roti' => 0, 'point' => 0];
        }

        $totalPoints = (int) round($grams / 0.01215);
        $bhori = intdiv($totalPoints, 960);
        $rem = $totalPoints % 960;
        $ana = intdiv($rem, 60);
        $rem = $rem % 60;
        $roti = intdiv($rem, 10);
        $point = $rem % 10;

        return [
            'bhori' => $bhori,
            'ana'   => $ana,
            'roti'  => $roti,
            'point' => $point,
        ];
    }

    /**
     * Convert traditional Bengali jewelry units (ভরি, আনা, রতি, পয়েন্ট) to grams.
     */
    public static function convertUnitsToGram($bhori = 0, $ana = 0, $roti = 0, $point = 0)
    {
        $totalPoints = (intval($bhori) * 960) + (intval($ana) * 60) + (intval($roti) * 10) + intval($point);
        return round($totalPoints * 0.01215, 3);
    }

    /**
     * Safely add raw stock and log history.
     */
    public static function addStock($categoryId, $grams, array $meta = [])
    {
        $grams = floatval($grams);
        if ($grams <= 0) {
            return null;
        }

        $category = ProductCategory::find($categoryId);
        $stock = self::firstOrCreate(
            ['category_id' => $categoryId],
            [
                'material_name' => ($category ? $category->category_name : 'Raw Material') . ' Stock',
                'gram'          => 0,
                'bhori'         => 0,
                'ana'           => 0,
                'roti'          => 0,
                'point'         => 0,
                'carat'         => 0,
            ]
        );

        $prevGram = floatval($stock->gram);
        $newGram = $prevGram + $grams;
        $units = self::convertGramToUnits($newGram);
        $addedUnits = self::convertGramToUnits($grams);

        $stock->gram  = number_format($newGram, 3, '.', '');
        $stock->bhori = $units['bhori'];
        $stock->ana   = $units['ana'];
        $stock->roti  = $units['roti'];
        $stock->point = $units['point'];
        
        // If Diamond, update carat (1 gram = 5 carat) or custom
        if ($category && (stripos($category->category_slug, 'diamond') !== false || stripos($category->category_name, 'diamond') !== false)) {
            $stock->carat = number_format(floatval($stock->carat) + (isset($meta['carat']) ? floatval($meta['carat']) : ($grams * 5)), 3, '.', '');
        }

        $stock->save();

        // Record audit history
        RawStockHistory::create([
            'raw_stock_id'    => $stock->id,
            'category_id'     => $categoryId,
            'karigor_job_id'  => $meta['karigor_job_id'] ?? null,
            'custom_order_id'          => $meta['custom_order_id'] ?? null,
            'purchase_id'              => $meta['purchase_id'] ?? null,
            'raw_material_purchase_id' => $meta['raw_material_purchase_id'] ?? null,
            'karigor_id'               => $meta['karigor_id'] ?? null,
            'type'                     => 'in',
            'gram'            => $grams,
            'bhori'           => $addedUnits['bhori'],
            'ana'             => $addedUnits['ana'],
            'roti'            => $addedUnits['roti'],
            'point'           => $addedUnits['point'],
            'carat'           => $meta['carat'] ?? ($grams * 5),
            'previous_gram'   => $prevGram,
            'current_gram'    => $newGram,
            'reason'          => $meta['reason'] ?? 'Raw stock added',
            'created_by'      => $meta['created_by'] ?? (auth()->check() ? auth()->id() : null),
            'notes'           => $meta['notes'] ?? null,
        ]);

        return $stock;
    }

    /**
     * Safely deduct raw stock and log history.
     */
    public static function deductStock($categoryId, $grams, array $meta = [])
    {
        $grams = floatval($grams);
        if ($grams <= 0) {
            return null;
        }

        $category = ProductCategory::find($categoryId);
        $stock = self::firstOrCreate(
            ['category_id' => $categoryId],
            [
                'material_name' => ($category ? $category->category_name : 'Raw Material') . ' Stock',
                'gram'          => 0,
                'bhori'         => 0,
                'ana'           => 0,
                'roti'          => 0,
                'point'         => 0,
                'carat'         => 0,
            ]
        );

        $prevGram = floatval($stock->gram);
        $newGram = max(0, $prevGram - $grams);
        $units = self::convertGramToUnits($newGram);
        $deductedUnits = self::convertGramToUnits($grams);

        $stock->gram  = number_format($newGram, 3, '.', '');
        $stock->bhori = $units['bhori'];
        $stock->ana   = $units['ana'];
        $stock->roti  = $units['roti'];
        $stock->point = $units['point'];

        if ($category && (stripos($category->category_slug, 'diamond') !== false || stripos($category->category_name, 'diamond') !== false)) {
            $stock->carat = max(0, floatval($stock->carat) - (isset($meta['carat']) ? floatval($meta['carat']) : ($grams * 5)));
        }

        $stock->save();

        // Record audit history
        RawStockHistory::create([
            'raw_stock_id'    => $stock->id,
            'category_id'     => $categoryId,
            'karigor_job_id'  => $meta['karigor_job_id'] ?? null,
            'custom_order_id'          => $meta['custom_order_id'] ?? null,
            'purchase_id'              => $meta['purchase_id'] ?? null,
            'raw_material_purchase_id' => $meta['raw_material_purchase_id'] ?? null,
            'karigor_id'               => $meta['karigor_id'] ?? null,
            'type'                     => 'out',
            'gram'            => $grams,
            'bhori'           => $deductedUnits['bhori'],
            'ana'             => $deductedUnits['ana'],
            'roti'            => $deductedUnits['roti'],
            'point'           => $deductedUnits['point'],
            'carat'           => $meta['carat'] ?? ($grams * 5),
            'previous_gram'   => $prevGram,
            'current_gram'    => $newGram,
            'reason'          => $meta['reason'] ?? 'Raw stock deducted for Karigor job',
            'created_by'      => $meta['created_by'] ?? (auth()->check() ? auth()->id() : null),
            'notes'           => $meta['notes'] ?? null,
        ]);

        return $stock;
    }
}
