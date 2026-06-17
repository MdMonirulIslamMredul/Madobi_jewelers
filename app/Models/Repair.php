<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function karigor()
    {
        return $this->belongsTo(User::class, 'karigor_id', 'id');
    }

    public function karigorProducts()
    {
        return $this->belongsTo(KarigorProduct::class, 'id', 'repair_id');
    }

    public function productCategory()
     {
         return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
     }
 
     //Relationship with product
     public function product()
     {
         return $this->belongsTo(Product::class, 'product_id', 'id');
     }

     public function transaction()
     {
         return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
     }
 
     public function invoice()
     {
         return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
     }
}
