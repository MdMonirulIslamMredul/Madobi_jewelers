<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterialPurchase extends Model
{
    use HasFactory;

    protected $table = 'raw_material_purchases';

    protected $fillable = [
        'raw_stock_id',
        'category_id',
        'invoice_no',
        'supplier_name',
        'supplier_phone',
        'purchase_date',
        'material_name',
        'karat',
        'gram',
        'bhori',
        'ana',
        'roti',
        'point',
        'carat',
        'unit_price',
        'rate_type',
        'total_amount',
        'paid_amount',
        'due_amount',
        'payment_method',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'gram'          => 'float',
        'bhori'         => 'integer',
        'ana'           => 'integer',
        'roti'          => 'integer',
        'point'         => 'integer',
        'carat'         => 'float',
        'unit_price'    => 'float',
        'total_amount'  => 'float',
        'paid_amount'   => 'float',
        'due_amount'    => 'float',
    ];

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }

    public function rawStock()
    {
        return $this->belongsTo(RawStock::class, 'raw_stock_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function histories()
    {
        return $this->hasMany(RawStockHistory::class, 'raw_material_purchase_id', 'id')->latest();
    }

    /**
     * Formatted string of traditional Bengali units
     */
    public function getFormattedWeightAttribute()
    {
        $parts = [];
        if ($this->bhori > 0) $parts[] = "{$this->bhori} ভরি";
        if ($this->ana > 0)   $parts[] = "{$this->ana} আনা";
        if ($this->roti > 0)  $parts[] = "{$this->roti} রতি";
        if ($this->point > 0) $parts[] = "{$this->point} পয়েন্ট";

        return !empty($parts) ? implode(' ', $parts) : "০ ভরি";
    }
}
