<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExplorationItem extends Model
{
    protected $fillable = [
        'exploration_id','type','value','index'
    ];
}
