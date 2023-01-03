<?php

namespace App;

use App\Topic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;

class Game extends Model
{

    protected $fillable = [
        'lesson_id','level','dynamic_number','points', 'title','question','feedback_ok','feedback_ko','images_ref'
    ];

    public function getLessonName($id){

        return DB::table('lessons')->where('id',$id)->value('name');
    }

    public function items()
    {
        return $this->hasMany('App\IteminGame','game_id');
    }

    public function getPreviousGame(){
        $lesson_id = $this->lesson_id;
        $level = $this->level;
        $game_id = $this->id;
        $games = Game::where('lesson_id',$lesson_id)->where('level',$level)->orderBy('id','DESC')->get();
        for($i=0;$i<count($games);$i++){
            if($games[$i]->id == $game_id && $i != (count($games)-1)){
                $previous_game = $games[$i+1];
            }elseif($games[$i]->id == $game_id && $i == (count($games)-1)){
                return false;
            }
        }

        return $previous_game->id;
    }


}
