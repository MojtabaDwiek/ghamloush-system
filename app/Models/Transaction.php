<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'safebox_id', 'type', 'amount', 'karat', 
        'to_safebox_id', 'description'
    ];
    
    public function safebox()
    {
        return $this->belongsTo(Safebox::class);
    }
    
    public function toSafebox()
    {
        return $this->belongsTo(Safebox::class, 'to_safebox_id');
    }
}
