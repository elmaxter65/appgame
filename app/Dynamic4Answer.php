<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dynamic4Answer extends Model
{
    protected $fillable = ['game_id','dynamic4_id', 'answer','correct_answer'];
}
