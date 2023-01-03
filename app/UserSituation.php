<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSituation extends Model
{
    protected $fillable = [
        'game_id','user_id','level','topic_id','lesson_id','round'
    ];
}
