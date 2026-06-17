<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;
    protected $fillable = [
        'stock_id','purchase_id', 'karat','product_id','category_id','ana','bhori','roti','point','gram','qty','status'
    ];

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }

    //Relationship with product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id', 'id');
    }
}
