<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPriceHistory extends Model
{
    use HasFactory;

    protected $table = 'product_price_histories';

    protected $fillable = [
        'product_price_id',
        'product_name',
        'buying_price',
        'buying_price_per_gram',
        'selling_price',
        'selling_price_per_gram',
        'price',
        'change_type',
        'changed_by',
        'effective_date',
        'note',
    ];

    protected $casts = [
        'buying_price'           => 'decimal:2',
        'buying_price_per_gram'  => 'decimal:2',
        'selling_price'          => 'decimal:2',
        'selling_price_per_gram' => 'decimal:2',
        'price'                  => 'decimal:2',
        'effective_date'         => 'datetime',
    ];

    public function productPrice()
    {
        return $this->belongsTo(ProductPrice::class, 'product_price_id');
    }

    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
