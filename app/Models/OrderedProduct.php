<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderedProduct extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'product_count',
        'unit_price_at_purchase',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
