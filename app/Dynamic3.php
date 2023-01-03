<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dynamic3 extends Model
{
    protected $fillable = ['dynamic_number', 'game_id', 'title', 'question','image','feedback_ok', 'feedback_ko'];
}
