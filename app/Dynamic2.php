<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dynamic2 extends Model
{
    protected $fillable = ['dynamic_number', 'game_id', 'title', 'question','image','correct_answer', 'feedback_ok', 'feedback_ko'];
}
