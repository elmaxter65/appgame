<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemporalPassword extends Model
{
    protected $fillable = [
        'user_id', 'password'
    ];
}
