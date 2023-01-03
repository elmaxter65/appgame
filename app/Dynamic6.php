<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dynamic6 extends Model
{
    protected $fillable = ['dynamic_number', 'game_id', 'title', 'question','feedback_ok', 'feedback_ko'];
}
