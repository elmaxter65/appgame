<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dynamic7Answer extends Model
{
    protected $fillable = ['game_id','dynamic7_id', 'image','correct_answer'];
}
