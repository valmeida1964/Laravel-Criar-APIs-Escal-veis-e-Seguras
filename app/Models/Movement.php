<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
