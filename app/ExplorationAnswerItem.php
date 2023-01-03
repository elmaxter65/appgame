<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExplorationAnswerItem extends Model
{
    protected $fillable = [
        'exploration_answers_id','value','percentage'
    ];
}
