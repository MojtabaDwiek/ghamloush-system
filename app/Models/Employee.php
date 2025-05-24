<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['name', 'phone'];
    
    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }
}

