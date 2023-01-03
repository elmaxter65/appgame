<?php

namespace App;

use App\TopicContent;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;


class Topic extends Model
{

    use Sortable;
    

    protected $fillable = [
        'name', 'points'
    ];
    public $sortable = ['name'];

    public function content()
    {
        return $this->hasOne('App\TopicContent','topics_id','id');
    }

    public function lessons()
    {
        return $this->hasMany('App\Lesson','topic_id','id');
    }

    public function games()
    {
        return $this->hasMany('App\Game','topics_id');
    }

    public function averageTimeSpent(){
        $time_spent_sec = TimeinLesson::where('topic_id',$this->id)->sum('time_spent_sec');

        if($time_spent_sec != 0){
            return round($time_spent_sec/60,2);
        }else{
            return $time_spent_sec;
        }
    }

}
