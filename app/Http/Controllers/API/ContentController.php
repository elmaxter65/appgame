<?php

namespace App\Http\Controllers\API;



use App\Topic;
use App\Lesson;
use App\UserApp;
use App\Achievement;
use App\TimeinLesson;
use App\TopicContent;
use App\UserResponse;
use App\UserAchievement;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;


class ContentController extends Controller
{

    public $successStatus = 200;

    public function getLessonScreen($topic_id)
    {
        $user = Auth::user();

        $lessons = DB::table('lessons')->where('topic_id', $topic_id)->get();
        foreach ($lessons as $lesson) {
            $num_games = DB::table('games')->where('lesson_id', $lesson->id)->where('level', '!=', 'challenge')->count();
            $lesson->num_games = $num_games;
            $num_games_user = 0;
            $lesson_id = $lesson->id;
            $games = DB::table('games')->where(function ($query) use ($lesson_id) {
                $query->where('lesson_id', $lesson_id)->where('level', '!=', 'challenge');
            })->get();
            foreach ($games as $game) {
                $user_response = DB::table('user_responses')->where('user_id', $user->id)->where('game_id', $game->id)->first();
                if ($user_response != null) {
                    $num_games_user += 1;
                }
            }
            $lesson->num_games_user = $num_games_user;

            // user answers in the current round
            $user = UserApp::find($user->id);
            $current_round = $user->getCurrentRound($lesson_id);
            $num_correct = 0;
            $num_incorrect = 0;
            $games_in_lesson = DB::table('games')->where('lesson_id',$lesson_id)->where('level','!=', 'challenge')->orderBy('level')->get();

            if($current_round == -1){
                $user_responses = "";
            }else{
                $user_responses = [];

                $num = 1;

                foreach($games_in_lesson as $game_in_lesson){
                    $game_lesson = (object)[];
                    $game_lesson->title = "Exercise " .  $num;
                    $game_lesson->subtitle = $game_in_lesson->title;
                    $user_response = UserResponse::where('user_id',$user->id)->where('game_id',$game_in_lesson->id)->first();
                    if($user_response != null){
                        $game_lesson->answer = $user_response->result;
                        if($user_response->result == 1){
                            $num_correct = $num_correct +1;
                        }else{
                            $num_incorrect = $num_incorrect +1;
                        }
                    }else{
                        $user_response = "";
                        $game_lesson->answer = -1;
                    }

                    $game_lesson->level = $game_in_lesson->level;
                    $num = $num+1;
                    array_push($user_responses,$game_lesson);
                }
            }

            $lesson->user_responses = $user_responses;
            $lesson->num_correct = $num_correct;
            $lesson->num_incorrect = $num_incorrect;



        }

        return response()->json(["lessons" => $lessons],  200);
    }

    public function getLessonContent($lesson_id, $spent = null)
    {
        
        $user = Auth::user();
        $topic_content = TopicContent::where('lesson_id', $lesson_id)->get();
        $topic_id = Lesson::find($lesson_id)->topic_id;
        // save time spent per lesson
        if($spent != null){
            $sec_spent = filter_var($spent,FILTER_VALIDATE_INT);

            TimeinLesson::create([
                'user_id' => $user->id,
                'lesson_id' => $lesson_id,
                'topic_id' => $topic_id,
                'time_spent_sec' => $sec_spent,
            ]);
        }

        $last_answer_object = UserResponse::where('user_id',$user->id)->where('lesson_id',$lesson_id)->orderBy('created_at', 'desc')->first();

        if($last_answer_object != null){
            if($last_answer_object->result == 1){
                $last_answer = 1;
            }else if($last_answer_object->result == 0){
                $last_answer = 2;
            }else{
                $last_answer = 0;
            }
        }else{
            $last_answer = 0;
        }

        return response()->json(["topic_content" => $topic_content, "last_answer" => $last_answer],  200);
    }

    public function getTopics()
    {
        $user = Auth::user();
        $user_id = $user->id;
        $topics = Topic::all();
        $num_topic = 0;
        foreach ($topics as $topic) {

            $num_lessons = DB::table('lessons')->where('topic_id', $topic->id)->count();
            $topic->num_lessons = $num_lessons;
            $lessons = DB::table('lessons')->where('topic_id', $topic->id)->get();
            $new = array();
            if ($num_lessons != 0) {
                foreach ($lessons as $lesson) {
                    $num_lessons_user = 0;
                    $is_lesson_completed = $this->isLessonCompleted($user_id, $lesson->id);

                    // check if that lesson has at least one game defined
                    $game_exist = DB::table('games')->where('lesson_id', $lesson->id)->get()->first();
                    if ($game_exist != null) {
                        $new[] = true;
                    } else {
                        $new[] = false;
                    }

                    if ($is_lesson_completed == true) {
                        $num_lessons_user += 1;
                    }
                }
                $topic->num_lessons_user = $num_lessons_user;
            } else {
                $topic->num_lessons_user = 0;
            }

            if (in_array(false, $new) || empty($new)) {
                $topic->new = 'develop';
            } else {
                $game_user = DB::table('game_points')->where('user_id', $user->id)->where('topic_id', $topic->id)->get()->first();
                // check if user has answered one of the games inside the topic
                if ($game_user != null) {
                    $topic->new = 'false';
                } else {
                    $topic->new = 'true';
                }
            }

            // check if the user has finished all the games correctly in this topic to show the challenge

            $lessons_topic = $topic->lessons()->get();
            $face_challenge = 'false';
            $game_id_array = array();
            foreach ($lessons_topic as $lesson_topic) {
                $games_topic = $lesson_topic->games()->get();
                foreach ($games_topic as $game_topic) {
                    $game_id_array[] = $game_topic->id;
                    $game_points = DB::table('game_points')->where('game_id', $game_topic->id)->where('user_id', $user->id)->get()->first();
                }
            }

            foreach ($game_id_array as $game_id) {

            $game_points = DB::table('game_points')->where('game_id', $game_id)->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)->where('points', "!=",0);
            })->get()->first();

                if($game_points == null){
                    $face_challenge = 'false';
                    break;
                }else{
                    $face_challenge = 'true';
                }

            }

            $colors = array('blue', 'yellow', 'purple', 'green', 'red');
            if ($num_topic >= count($colors)) {
                $num_topic = 0;
            }
            $topic->face_challenge = $face_challenge;
            $color = Config::get("styles." . $colors[$num_topic]);
            $topic->first_color = $color['first'];
            $topic->second_color = $color['second'];
            $topic->third_color = $color['third'];
            $topic->cod_image = (string) $num_topic;

            $num_topic += 1;
        }


        return response()->json(["topics" => $topics],  200);
    }

    public function isLessonCompleted($user_id, $lesson_id)
    {

        $games = DB::table('games')->where('lesson_id', $lesson_id)->get();
        $res = false;
        foreach ($games as $game) {

            $game_id = $game->id;
            $game_user = DB::table('game_points')->where('user_id', $user_id)->where('game_id', $game_id)->get()->first();
            if ($game_user == null) {
                $res = false;
                break;
            } else {
                $res = true;
            }
        }
        if ($res == false) {
            return false;
        } else {
            return true;
        }
    }

    public function getLessons()
    {
        $user = Auth::user();

        $lessons = Lesson::all();

        return response()->json(["lessons" => $lessons],  200);
    }

    public function getAchievements()
    {
        $user = Auth::user();
        $achievements = Achievement::all();

        $user = Auth::user();
        $user_achievements = UserAchievement::where('user_id', $user->id)->get();
        foreach ($user_achievements as $user_achievement) {

            $achievement_title =  DB::table('achievements')->where('id', $user_achievement->achievement_id)->get()->first();
            $achievement_description =  DB::table('achievements')->where('id', $user_achievement->achievement_id)->get('description')->first();
            $user_achievement->achievement_title = $achievement_title->title;
            $user_achievement->achievement_description = $achievement_description->description;
        }

        return response()->json(["achievements" => $achievements, "user_achievements" => $user_achievements],  200);
    }

    public function getAchievement($achievement_id)
    {
        $achievement = Achievement::where('id', $achievement_id)->get()->first();

        if ($achievement != null) {
            return response()->json(["achievement" => $achievement],  200);
        } else {
            return response()->json(["achievement" => 'false'],  200);
        }
    }




}
