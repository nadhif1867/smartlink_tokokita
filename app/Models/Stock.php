<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'quantity',
        'unit_price',
        'date_received',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    
}
