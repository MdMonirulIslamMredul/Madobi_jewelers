<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstantSellItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'bhori'         => 'decimal:4',
        'ana'           => 'decimal:4',
        'roti'          => 'decimal:4',
        'point'         => 'decimal:4',
        'gram'          => 'decimal:4',
        'purchase_cost' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'profit'        => 'decimal:2',
    ];

    public function instantSell()
    {
        return $this->belongsTo(InstantSell::class, 'instant_sell_id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }
}
