<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'type','index','value'
    ];

    public function item()
{
    return $this->belongsTo('App\IteminGame');
}

    public function answer(){
        return $this->hasMany('App\AnswersinItem');
    }
}
