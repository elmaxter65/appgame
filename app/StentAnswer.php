<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StentAnswer extends Model
{
    protected $fillable = [
        'stent_id','value','percentage'
    ];
}
