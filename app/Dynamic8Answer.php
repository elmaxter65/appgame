<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dynamic8Answer extends Model
{
    protected $fillable = ['game_id','dynamic8_id', 'answer','correct_answer'];
}
