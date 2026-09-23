<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawStockHistory extends Model
{
    use HasFactory;

    protected $table = 'raw_stock_histories';

    protected $fillable = [
        'raw_stock_id',
        'category_id',
        'karigor_job_id',
        'custom_order_id',
        'purchase_id',
        'raw_material_purchase_id',
        'karigor_id',
        'type',
        'gram',
        'bhori',
        'ana',
        'roti',
        'point',
        'carat',
        'previous_gram',
        'current_gram',
        'reason',
        'created_by',
        'notes',
    ];

    public function rawStock()
    {
        return $this->belongsTo(RawStock::class, 'raw_stock_id', 'id');
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }

    public function rawMaterialPurchase()
    {
        return $this->belongsTo(RawMaterialPurchase::class, 'raw_material_purchase_id', 'id');
    }

    public function karigorJob()
    {
        return $this->belongsTo(KarigorJob::class, 'karigor_job_id', 'id');
    }

    public function customOrder()
    {
        return $this->belongsTo(CustomOrder::class, 'custom_order_id', 'id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }

    public function karigor()
    {
        return $this->belongsTo(User::class, 'karigor_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
