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
        'price',
    ];
}
?>
