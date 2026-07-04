<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function category()
    {
        return $this->belongTo(Category::class);
    }

    public function movements()
    {
        return $this->hasMany(Movement::class);
    }

    
}
