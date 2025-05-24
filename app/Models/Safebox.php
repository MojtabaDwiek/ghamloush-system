<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Safebox extends Model
{
    protected $fillable = ['karat', 'balance', 'description'];
    
    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
