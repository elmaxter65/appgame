<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TreatmentAnswer extends Model
{
    protected $fillable = [
        'treatment_id','value','percentage'
    ];
}
