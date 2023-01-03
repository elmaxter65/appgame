<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IteminGame extends Model
{
    protected $fillable = [
        'game_id','item_id'
    ];

  
    public function game()
{
    return $this->belongsTo('App\Game');
}
    public function content()
{
    return $this->hasOne('App\Item','item_id','id');
}

}
