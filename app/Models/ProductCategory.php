<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    //Relationship with products
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    //Relationship with purchases
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function karigors()
    {
        return $this->hasMany(Karigor::class);
    }
}
