<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstantSell extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'total_cost'           => 'decimal:2',
        'subtotal'             => 'decimal:2',
        'karigor_mojuri_rate'  => 'decimal:2',
        'karigor_mojuri_total' => 'decimal:2',
        'discount'             => 'decimal:2',
        'vat_tax'              => 'decimal:2',
        'grand_total'          => 'decimal:2',
        'total_profit'         => 'decimal:2',
        'paid_amount'          => 'decimal:2',
        'due_amount'           => 'decimal:2',
        'due_date'             => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function karigorMojuri()
    {
        return $this->belongsTo(KarigorMojuri::class, 'karigor_mojuri_id');
    }

    public function items()
    {
        return $this->hasMany(InstantSellItem::class, 'instant_sell_id');
    }

    public function payments()
    {
        return $this->hasMany(SellPayment::class, 'instant_sell_id')->orderBy('payment_step', 'asc');
    }
}
