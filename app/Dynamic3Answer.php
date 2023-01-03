<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dynamic3Answer extends Model
{
    protected $fillable = ['game_id','dynamic3_id', 'answer','correct_answer'];
}
