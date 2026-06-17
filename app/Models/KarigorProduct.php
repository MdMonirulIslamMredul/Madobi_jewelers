<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KarigorProduct extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function repair()
    {
        return $this->hasMany(Repair::class, 'id', 'repair_id');
    }
    public function warehouse()
    {
        return $this->hasMany(Warehouse::class, 'warehouse_id', 'id');
    }

     //Relationship with productCategory
     public function productCategory()
     {
         return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
     }
     public function ConvertedproductCategory()
     {
         return $this->belongsTo(ProductCategory::class, 'converted_category_id', 'id');
     }
 
     //Relationship with product
     public function Convertedproduct()
     {
         return $this->belongsTo(Product::class, 'converted_product_id', 'id');
     }
     public function product()
     {
         return $this->belongsTo(Product::class, 'product_id', 'id');
     }
}
