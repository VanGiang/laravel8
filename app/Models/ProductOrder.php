<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ProductOrder extends Model
{
    protected $table = 'product_order';
    protected $fillable = [
        'product_id',
        'order_id',
        'quantity',
        'price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
