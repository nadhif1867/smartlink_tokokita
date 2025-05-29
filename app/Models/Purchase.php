<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'quantity',
        'unit_price',
        'purchase_date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
