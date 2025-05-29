<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'quantity',
        'selling_price_per_unit',
        'cost_of_goods_sold', 
        'sale_date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    
}
