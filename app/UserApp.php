<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\CustomerResetPasswordNotification;

class UserApp extends Authenticatable
{
    use HasApiTokens,Sortable,Notifiable;


    public function routeNotificationForMail($notification)
  {
    return decrypt($this->email);
  }


    protected $guard = 'user_apps';

    protected $fillable = [
        'name','email','password','profile_image','points', 'state', 'hospital','status','password_changed_at','check_comercial','family_name','city','country','nickname','check_policy','avatar_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public $sortable = ['name','email','state'];

    public function profileImage($id)
    {
        $user = UserApp::find($id);
        $profile_image = $user->profile_image;
        if($profile_image != null){
            return asset('storage/users/'.$profile_image);
        }else{
            return "images/avatar-blank.png";
        }

    }

    public function getUserRanking(){
        $users_list = DB::table('user_apps')->orderBy('points', 'DESC')->get(['id', 'name', 'nickname', 'points', 'status']);
        $ind = 1;
        foreach( $users_list as $user){
            if($user->id == $this->id){
                return $ind;
            }
            $ind = $ind + 1;

        }

        return $ind;
    }


    public function getUserStatus(){
        $user_id = $this->id;

        $ranking = DB::table('statuses')->get(['name', 'points']);
        $user_points = $this->points;
        $user_status = $ranking[0];
        foreach ($ranking as $rank) {
            $limit = $rank->points;
            if ($user_points < $limit) {
                $user_status = $rank->name;
                break;
            }
        }
        return $user_status;

    }

    public function blockUser(){

        $this->blocked = true;
        $this->blocked_time = Carbon::now();
        $this->save();
    }

    public function isUserBlocked(){

        if($this->blocked == true && (Carbon::now()->diffInMinutes($this->blocked_time) < config('custom.blocked_waiting_time'))){
            return true;
        }else{
            return false;
        }
    }

    public function unblockUser(){

        $this->blocked = false;
        $this->blocked_time = null;
        $this->save();
    }

    public function setUserSituation($game_level, $user_id, $topic_id,$game,$lesson_id,$same_round){

        if ($game_level == "challenge") {

            $exists = UserSituation::where('user_id', $user_id)->where(function ($query) use ($topic_id) {
                $query->where('topic_id', $topic_id)->where('level', 'challenge');
            })->get()->first();

            if ($exists == null) {
                $userSituation = UserSituation::create([
                    'game_id' => $game->id,
                    'user_id' => $user_id,
                    'level' => $game->level,
                    'topic_id' => $topic_id,
                    'lesson_id' => $lesson_id,
                    'round' => 0,
                ]);
            } else {

                // if same_round = 1 means I have to go back 1 game
                if($same_round == true){
                    $game = Game::find($game->id);
                    $previous_game = $game->getPreviousGame();

                    if($previous_game == false){
                        $exists->delete();
                    }else{
                        $exists->game_id = $previous_game;
                    }
                }else{
                    $exists->game_id = $game->id;
                }

                $exists->level = $game->level;

                $exists->save();
            }
        } else {

            $exists = UserSituation::where('user_id', $user_id)->where('lesson_id', $lesson_id)->get()->first();

            if ($exists == null) {
                $userSituation = UserSituation::create([
                    'game_id' => $game->id,
                    'user_id' => $user_id,
                    'level' => $game->level,
                    'topic_id' => $topic_id,
                    'lesson_id' => $lesson_id,
                    'round' => 0,
                ]);
            } else {
                // if same_round = 1 means I have to go back 1 game
                if($same_round == true){

                    $game = Game::find($game->id);
                    $previous_game = $game->getPreviousGame();

                    if($previous_game == false){
                        $exists->delete();
                    }else{
                        $exists->game_id = $previous_game;
                    }
                }else{
                    $exists->game_id = $game->id;
                }

                $exists->level = $game->level;

                $exists->save();
            }
        }

    }

    public function isTokenActive(){
        $userToken = TokenNotification::where('user_id', $this->id)->get();
        $userTokens = [];
        if(! $userToken->isEmpty()){
            return $userToken[0]->active;
        }else{
            return false;
        }
    }

    public function getNumAchievement(){
        return count(UserAchievement::where('user_id',$this->id)->get());

    }

    public function getCurrentRound($lesson_id){
        $user_situation = UserSituation::where('user_id',$this->id)->where('lesson_id',$lesson_id)->first();

        if($user_situation != null){
            return $user_situation->round;
        }else{
            return -1;
        }
    }

    public function getGamesPlayed(){
        return UserResponse::where('user_id',$this->id)->distinct('game_id')->count('game_id');
    }

    public function getLessonsPlayed(){
        // we consider a lesson is played if the user has playes one of the games of the lesson
        $lessons = Lesson::all();
        $num = 0;
        foreach($lessons as $lesson){
            $lesson_id = $lesson->id;
            $games = Game::where('lesson_id',$lesson_id)->get();
            foreach($games as $game){
                $game_id = $game->id;
                $user_response = UserResponse::where('game_id',$game_id)->where('user_id',$this->id)->get();
                if(!$user_response->isEmpty()){
                    $num = $num + 1;
                    break;
                }
            }
        }

        return $num;

    }

    public function getGamesPlayedInLesson($lesson_id){
        $games = Game::where('lesson_id',$lesson_id)->get();
        $num = 0;
        foreach($games as $game){
            $game_id = $game->id;
            $user_response = UserResponse::where('game_id',$game_id)->where('user_id',$this->id)->get();
            if(!$user_response->isEmpty()){
                $num = $num + 1;

            }
        }
        return $num;
    }

    public function getGamesPlayedDetails(){
        $games = UserResponse::where('user_id',$this->id)->get();
        $games_array = array();

        if(!$games->isEmpty()){
        foreach($games as $game){
            $game_id = $game->game_id;
            $games_answers = GamePoint::where('user_id',$this->id)->where('game_id',$game_id)->get();
            $answer_true = 0;
            $answer_false = 0;
            foreach($games_answers as $game_answer){
                if($game_answer->points != 0){
                    $answer_true = $answer_true + 1;
                }else{
                    $answer_false = $answer_false + 1;
                }
            }
            $game = Game::find($game_id);
            if($game != null){
                $game_name = $game->title;
                $lesson_name = Lesson::find($game->lesson_id)->name;
                $game_detail = array('lesson_name'=> $lesson_name,"question_name"=>$game_name, "times_right"=>$answer_true, "times_wrong"=>$answer_false);
                array_push($games_array,$game_detail);
            }

        }
    }
        return $games_array;
    }


    public function numberSessions(){
        return Token::where('user_id',$this->id)->count();
    }
}
