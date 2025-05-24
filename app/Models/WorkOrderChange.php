<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderChange extends Model
{
    protected $fillable = ['work_order_id', 'safebox_id', 'weight', 'note'];
    
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
    
    public function safebox()
    {
        return $this->belongsTo(Safebox::class);
    }
}
