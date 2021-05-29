<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatistic extends Model
{
    protected $table = 'order_statistics';
    protected $fillable = [
        'date',
        'total_order',
        'total_price',
    ];
}
