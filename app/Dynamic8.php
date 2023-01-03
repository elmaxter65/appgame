<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dynamic8 extends Model
{
    protected $fillable = ['dynamic_number', 'game_id', 'title', 'question','text_before', 'text_after','feedback_ok', 'feedback_ko'];
}
