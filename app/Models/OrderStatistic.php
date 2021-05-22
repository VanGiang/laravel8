<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatistic extends Model
{
    protected $table = 'order_statistics';
    protected $fillable = [
        'date',
        'total_price',
        'total_order',
    ];
}
