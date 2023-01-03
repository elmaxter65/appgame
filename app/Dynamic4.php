<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dynamic4 extends Model
{
    protected $fillable = ['dynamic_number', 'game_id', 'title', 'question','feedback_ok', 'feedback_ko'];
}
