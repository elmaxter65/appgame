<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExplorationItemChild extends Model
{
    protected $fillable = [
        'exploration_item_id','type','value'
    ];
}
