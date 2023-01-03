<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\DB;

class Lesson extends Model
{

    use Sortable;

    protected $fillable = [
        'name','topic_id','points'
    ];

    public $sortable = ['name','topic_id'];

    public function getTopic($id){
        $topic_name = DB::table('topics')->where('id', $id)->value('name');
        return $topic_name;
    }

    public function topic()
{
    return $this->belongsTo('App\Topic','topic_id','id');
}

    public function games()
{
    return $this->hasMany('App\Game')->where('level','!=','challenge');
}

public function gamesChallenge()
{
    $games_challenge = DB::table('games')->where('lesson_id', $this->id)->where('level','challenge')->get();
    return $games_challenge;
}

public function averageNumGamesCompleted(){
    
    $users = UserApp::all();
    $num_users = $users->count();
    if($num_users == 0){
        return 0;
    }
    $numGamesUser = 0;
    $lesson_id = $this->id;
    foreach($users as $user){
        $numGamesUser += GamePoint::where('user_id',$user->id)->where(function ($query) use ($lesson_id) {
            $query->where('lesson_id', $lesson_id)->where('points', '!=', 0);
        })->count();
    }

    return number_format($numGamesUser/$num_users,'2','.','');
    
}

public function averagePercGamesCompleted(){
    $averageNum = $this->averageNumGamesCompleted();
    if($averageNum != 0){
        return number_format(($averageNum/($this->games()->count()))*100,'2','.','');
    }else{
        return 0;
    }
}

public function colorPercGamesCompleted(){
    $percentage = $this->averagePercGamesCompleted();
    if($percentage == 100){
        return "bg-success";
    }elseif(70<=$percentage && $percentage<100){
        return "bg-info";
    }elseif(50<=$percentage && $percentage<70){
        return "bg-warning";
    }else{
        return "bg-danger";
    }
}

public function numMaxRounds(){
    $num_max_rounds = GamePoint::select('round')->where('lesson_id',$this->id)->distinct()->count();

    return $num_max_rounds;
}

public function averagePointsPerRound($round){
    $users = UserApp::all();
    $num_users = $users->count();
    if($num_users == 0){
        return 0;
    }
    $lesson_id = $this->id;

    $pointsUser = 0;
    foreach($users as $user){
        $points = GamePoint::where('user_id',$user->id)->where(function ($query) use ($lesson_id,$round) {
            $query->where('lesson_id', $lesson_id)->where('round', $round);
        })->first();
        if($points != null){
            $pointsUser += $points->points;

        }
        
    }

    return number_format($pointsUser/$num_users,'2','.','');
}

public function numTotalPoints(){
    return Game::where('lesson_id',$this->id)->sum('points');
}

    
}
