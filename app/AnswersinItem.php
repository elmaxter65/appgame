<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnswersinItem extends Model
{
    protected $fillable = [
        'answer_id','item_id'
    ];

    public function item()
{
    return $this->belongsTo('App\Item');
}

public function content()
{
    return $this->hasOne('App\Answer','answer_id','id');
}
}
