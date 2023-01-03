<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dynamic5Answer extends Model
{
    protected $fillable = ['game_id','dynamic5_id', 'answer_position','answer'];
}
