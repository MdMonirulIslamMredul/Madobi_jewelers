<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','category_id', 'product_id','details','total_price','adv_payment','due_payment','due_payment_date','total_payment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function bondhoks()
    {
        return $this->hasMany(Bondhok::class, 'transaction_id');
    }
    public function bondhok_product()
    {
        return $this->belongsTo(Bproduct::class, 'product_id', 'id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'transaction_id');
    }

    public function sells()
    {
        return $this->hasMany(Sell::class, 'transaction_id');
    }

    public function repairs()
    {
        return $this->hasMany(Repair::class, 'transaction_id');
    }

       //Relationship with productCategory
       public function productCategory()
       {
           return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
       }
   
       //Relationship with product
       public function product()
       {
           return $this->belongsTo(Product::class, 'product_id', 'id');
       }
}
