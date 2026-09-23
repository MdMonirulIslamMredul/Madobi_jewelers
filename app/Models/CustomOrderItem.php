<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomOrderItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'target_bhori'       => 'decimal:4',
        'target_ana'         => 'decimal:4',
        'target_roti'        => 'decimal:4',
        'target_point'       => 'decimal:4',
        'target_gram'        => 'decimal:4',
        'raw_gold_needed'    => 'decimal:4',
        'actual_weight_gram'  => 'decimal:4',
        'unit_price_per_gram' => 'decimal:2',
        'estimated_price'     => 'decimal:2',
        'karigor_fee'         => 'decimal:2',
        'actual_price'        => 'decimal:2',
    ];

    public function customOrder()
    {
        return $this->belongsTo(CustomOrder::class, 'custom_order_id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function karigor()
    {
        return $this->belongsTo(User::class, 'assigned_karigor_id');
    }

    public function karigorJob()
    {
        return $this->belongsTo(KarigorJob::class, 'karigor_job_id');
    }
}
