<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'image',
        'unit',
        'stock_quantity',
        'unit_price',
    ];

    public function orderedProducts()
    {
        return $this->hasMany(OrderedProduct::class);
    }
}
