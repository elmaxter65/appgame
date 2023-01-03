<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\DB;

class TimeinLesson extends Model
{

    protected $table = 'time_in_lesson';

    protected $fillable = [
        'user_id','lesson_id','time_spent_sec'
    ];


    

    
}
