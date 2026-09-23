<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KarigorMojuri extends Model
{
    use HasFactory;

    protected $table = 'karigor_mojuris';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'category_name',
        'type',
        'per_vori_tk',
        'per_gram_tk',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'per_vori_tk' => 'decimal:2',
        'per_gram_tk' => 'decimal:2',
    ];

    /**
     * Support 'catergory_name' alias if accessed by typo spelling.
     */
    public function getCatergoryNameAttribute()
    {
        return $this->category_name;
    }

    public function setCatergoryNameAttribute($value)
    {
        $this->attributes['category_name'] = $value;
    }
}
