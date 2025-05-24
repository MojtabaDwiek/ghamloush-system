<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Constants\WorkOrderTypes; // Add this import
use Carbon\Carbon; // Add Carbon import for date handling

class WorkOrder extends Model
{
    protected $fillable = [
        'employee_id', 
        'safebox_id', 
        'type', 
        'start_amount', 
        'finish_amount', 
        'start_date', 
        'end_date', 
        'loss', 
        'status'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_amount' => 'decimal:2',
        'finish_amount' => 'decimal:2',
        'loss' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'type_name',
        'type_slug',
        'formatted_start_date',
        'formatted_end_date'
    ];
    
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function safebox()
    {
        return $this->belongsTo(Safebox::class);
    }
    
    public function getTypeNameAttribute()
    {
        return WorkOrderTypes::TYPES[$this->type] ?? $this->type;
    }

    public function getTypeSlugAttribute()
    {
        return $this->type;
    }
    
    public function getFormattedStartDateAttribute()
    {
        return $this->start_date 
            ? Carbon::parse($this->start_date)->format('Y-m-d')
            : null;
    }
    
    public function getFormattedEndDateAttribute()
    {
        return $this->end_date 
            ? Carbon::parse($this->end_date)->format('Y-m-d')
            : null;
    }
    
    public function changes()
    {
        return $this->hasMany(WorkOrderChange::class);
    }
    
    /**
     * Scope for pending work orders
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    /**
     * Scope for completed work orders
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}