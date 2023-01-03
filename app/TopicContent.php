<?php

namespace App;

use App\Topic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;

class TopicContent extends Model
{

    use Sortable;

    protected $fillable = [
        'content','topics_id','lesson_id','heading'
    ];

    public $sortable = ['id','topics_id','lesson_id'];

    public function topic()
    {
        return $this->belongsTo('App\Topic','topics_id','id');
    }
    public function lesson()
    {
        return $this->belongsTo('App\Lesson','lesson_id','id');
    }

    public function topicName($id)
    {
        return DB::table('topics')->where('id', $id)->value('name');
    }
    public function lessonName($id)
    {
        return DB::table('lessons')->where('id', $id)->value('name');
    }
}
