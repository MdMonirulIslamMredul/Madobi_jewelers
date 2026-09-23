<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KarigorJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'custom_order_id',
        'karigor_id',
        'assigned_by',
        'task_type',
        'status',
        'given_gross_weight',
        'given_purity_weight',
        'assigned_extra_raw_gold',
        'returned_gross_weight',
        'returned_raw_gold',
        'used_extra_raw_gold',
        'wastage_gold',
        'conversion_percentage',
        'is_raw_material_given',
        'raw_material_category_id',
        'given_raw_material',
        'assigned_at',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'assigned_at'           => 'datetime',
        'completed_at'          => 'datetime',
        'is_raw_material_given' => 'boolean',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function customOrder()
    {
        return $this->belongsTo(CustomOrder::class, 'custom_order_id');
    }

    public function karigor()
    {
        return $this->belongsTo(User::class, 'karigor_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function rawMaterialCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'raw_material_category_id');
    }

    public function rawStockHistories()
    {
        return $this->hasMany(RawStockHistory::class, 'karigor_job_id');
    }
}
