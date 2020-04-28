<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //

    protected $casts = [
        'items' => 'array',
    ];

    protected $primaryKey = 'id';

    protected $table = 'orders';

    protected $fillable = ['items', 'address', 'delivery_time'];
}
