<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','category_id','transaction_id', 'invoice_no', 'product_id','details','total_price','adv_payment','due_payment','due_payment_date','total_payment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function bondhoks()
    {
        return $this->hasMany(Bondhok::class, 'invoice_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'invoice_id');
    }

    public function sells()
    {
        return $this->hasMany(Sell::class, 'invoice_id');
    }

    public function transactions()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }

    public function repairs()
    {
        return $this->hasMany(Repair::class, 'invoice_id');
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
