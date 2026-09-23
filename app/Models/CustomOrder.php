<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomOrder extends Model
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
        'actual_weight_gram' => 'decimal:4',
        'karigor_fee'        => 'decimal:2',
        'estimated_price'    => 'decimal:2',
        'actual_price'       => 'decimal:2',
        'discount'           => 'decimal:2',
        'grand_total'        => 'decimal:2',
        'paid_amount'        => 'decimal:2',
        'due_amount'         => 'decimal:2',
        'due_date'           => 'date',
        'order_date'         => 'date',
        'delivery_date'      => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function karigor()
    {
        return $this->belongsTo(User::class, 'assigned_karigor_id');
    }

    public function karigorJob()
    {
        return $this->belongsTo(KarigorJob::class, 'karigor_job_id');
    }

    public function items()
    {
        return $this->hasMany(CustomOrderItem::class, 'custom_order_id');
    }

    public function payments()
    {
        return $this->hasMany(SellPayment::class, 'custom_order_id')->orderBy('payment_step', 'asc');
    }
}
