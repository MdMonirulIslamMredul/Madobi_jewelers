<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KarigorJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'karigor_id',
        'assigned_by',
        'task_type',
        'status',
        'given_gross_weight',
        'given_purity_weight',
        'assigned_extra_raw_gold',
        'returned_gross_weight',
        'returned_raw_gold',
        'used_extra_raw_gold',
        'wastage_gold',
        'conversion_percentage',
        'assigned_at',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function karigor()
    {
        return $this->belongsTo(User::class, 'karigor_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
