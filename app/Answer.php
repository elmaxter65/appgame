<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'value', 'is_correct', 'percentage'
    ];

    public function answer()
    {
        return $this->belongsTo('App\AnswersinItem');
    }
}
