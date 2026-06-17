<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $fillable = [
        'karat','product_id','category_id','ana','bhori','roti','point','gram','qty','unit_price','location'
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

    public function shop()
    {
        return $this->hasMany(Shop::class);
    }

    public function warehouse()
    {
        return $this->hasMany(Warehouse::class);
    }
}
