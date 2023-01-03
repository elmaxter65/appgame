<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LiveCase extends Model
{
    protected $fillable = [
        'title','main_img','patient_name','patient_age', 'patient_sex','med_history','symptoms','difficulty_level','topic_id','points'
    ];
}
