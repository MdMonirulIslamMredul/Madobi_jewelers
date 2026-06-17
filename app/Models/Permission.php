<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    //Relationship with Role
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withPivot('role_id');
    }

    //Relationship with Module
    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }
}
