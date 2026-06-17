<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BProduct extends Model
{
    use HasFactory;

    public function bondhoks()
    {
        return $this->hasMany(Bondhok::class, 'product_type_id');
    }
}
