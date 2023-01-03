<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dynamic1 extends Model
{
    protected $fillable = ['dynamic_number', 'game_id', 'title', 'question','image_1','image_2','correct_answer', 'feedback_ok', 'feedback_ko'];
}
