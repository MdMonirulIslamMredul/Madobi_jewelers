<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellPayment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'amount'       => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    public function instantSell()
    {
        return $this->belongsTo(InstantSell::class, 'instant_sell_id');
    }

    public function customOrder()
    {
        return $this->belongsTo(CustomOrder::class, 'custom_order_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
