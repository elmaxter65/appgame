<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TokenNotification extends Model
{
    protected $table = 'tokens';
    protected $fillable = [
       'token', 'user_id'
    ];
}
