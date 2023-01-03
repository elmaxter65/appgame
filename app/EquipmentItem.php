<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EquipmentItem extends Model
{
    protected $fillable = [
        'equipment_id','type','value','index','percentage'
    ];
}
