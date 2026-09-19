<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    use HasFactory;

    protected $table = 'product_price';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'product_name',
        'buying_price',
        'buying_price_per_gram',
        'selling_price',
        'selling_price_per_gram',
        'price',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'buying_price'           => 'decimal:2',
        'buying_price_per_gram'  => 'decimal:2',
        'selling_price'          => 'decimal:2',
        'selling_price_per_gram' => 'decimal:2',
        'price'                  => 'decimal:2',
    ];

    /**
     * Get the price histories for the product price.
     */
    public function histories()
    {
        return $this->hasMany(ProductPriceHistory::class, 'product_price_id')->orderBy('created_at', 'desc');
    }
}
?>
