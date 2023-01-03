<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GamePoint extends Model
{
    protected $fillable = [
        'game_id', 'topic_id', 'user_id','points', 'round','lesson_id'
    ];
}
