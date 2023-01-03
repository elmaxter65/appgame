<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAchievement extends Model
{
    protected $fillable = [
        'user_id', 'achievement_id','points'
    ];
}
