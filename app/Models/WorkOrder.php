<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    protected $fillable = [
        'employee_id', 'safebox_id', 'type_id', 
        'start_amount', 'finish_amount', 
        'start_date', 'end_date', 'loss', 'status'
    ];
    
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function safebox()
    {
        return $this->belongsTo(Safebox::class);
    }
    
    public function type()
    {
        return $this->belongsTo(WorkOrderType::class);
    }
    
    public function changes()
    {
        return $this->hasMany(WorkOrderChange::class);
    }
}
