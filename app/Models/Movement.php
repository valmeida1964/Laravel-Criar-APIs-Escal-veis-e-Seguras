<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'movement_type'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
