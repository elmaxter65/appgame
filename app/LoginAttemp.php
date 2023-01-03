<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginAttemp extends Model
{
    protected $fillable = [
        'num_attemps','user_id','user_cms_id'
    ];
}
