<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseLocationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'karigor_job_id',
        'from_location',
        'to_location',
        'transferred_by',
        'assigned_karigor_id',
        'task_type',
        'extra_raw_gold',
        'note',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function karigorJob()
    {
        return $this->belongsTo(KarigorJob::class, 'karigor_job_id');
    }

    public function transferredBy()
    {
        return $this->belongsTo(User::class, 'transferred_by');
    }

    public function karigor()
    {
        return $this->belongsTo(User::class, 'assigned_karigor_id');
    }
}
