<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderType extends Model
{
    protected $fillable = ['name', 'slug'];
    
    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class, 'type_id');
    }
}
