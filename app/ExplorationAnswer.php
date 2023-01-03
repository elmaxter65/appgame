<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExplorationAnswer extends Model
{
    protected $fillable = [
        'header','exploration_id'
    ];
}
