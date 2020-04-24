<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    //
    protected $casts = [
        'ingredient' => 'array',
    ];

    protected $primaryKey = 'id';

    protected $table = 'recipes';
}
