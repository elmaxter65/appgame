<?php

namespace App\Http\Controllers\API;

use App\Game;
use App\Topic;
use Validator;
use App\UserApp;
use App\GamePoint;
use App\Achievement;
use App\UserResponse;
use App\UserSituation;
use App\UserAchievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{

    public $successStatus = 200;

    public function getGamesinLesson($lesson_id,$repeat = null)
    {

        $user = Auth::user();
        $user_id = $user->id;

        $games = DB::table('games')->where(function ($query) use ($lesson_id) {
            $query->where('lesson_id', $lesson_id)->where('level', '!=', 'challenge');
        })->get();

        // if repeat = 1, the user has pressed RREPEAT TEST. If repeat = 0 it has pressed TAKE THE TEST (nothing happens)

        if($repeat == 1){
            $same_round = true;

            $user_answer = UserApp::where('id',$user_id)->select('points','state','status')->get()->first();

            // find the game
            $last_answer_object = UserResponse::where('user_id',$user->id)->where('lesson_id',$lesson_id)->orderBy('created_at', 'desc')->first();
            if($last_answer_object != null){

                $game_id = $last_answer_object->game_id;
                $game = Game::find($game_id);
                // set the situation of the user
                $lesson_id = $game->lesson_id;
                $topic = DB::table('lessons')->where('id', $lesson_id)->get('topic_id')->first();
                $topic_id = $topic->topic_id;
                $game_level = $game->level;
                // set user situation
                $user_answer->setUserSituation($game_level, $user_id, $topic_id,$game,$lesson_id,$same_round);


                // delete the results for that game (last round)
                $userResponse_exist = UserResponse::where('user_id', $user_id)->where('game_id', $game_id)->get()->first();
                if($userResponse_exist != null){
                    $userResponse_exist->delete();
                }

                $exist_last = GamePoint::where('game_id', $game_id)->where('user_id', $user_id)->get()->last();
                if($exist_last != null){
                    $exist_last->delete();
                }

            }


        }

        foreach ($games as $game) {
            $game_id = $game->id;
            $items_id = DB::table('itemin_games')->where('game_id', $game_id)->get('item_id');
            //$items = DB::table('itemin_games')->where('game_id',$game_id)->get();
            //$i = 0;
            $items = array();
            foreach ($items_id as $item_id) {
                $answers_id = DB::table('answersin_items')->where('item_id', $item_id->item_id)->get('answer_id');
                $item = DB::table('itemin_games')->where('item_id', $item_id->item_id)->get()->first();
                $item_data = DB::table('items')->where('id', $item_id->item_id)->get()->first();
                $item->type = $item_data->type;
                $item->index = $item_data->index;
                $item->value = $item_data->value;
                $answers_array = array();
                foreach ($answers_id as $answer_id) {
                    $answers = DB::table('answers')->where('id', $answer_id->answer_id)->get();
                    $answers_array[] = $answers;
                }

                $item->answers = $answers_array;

                $items[] = $item;

                //$i++;
            }

            $user_response = DB::table('user_responses')->where('user_id', $user_id)->where('game_id', $game_id)->get('result')->first();

            if ($user_response == null) {
                $game->user_response = "";
            } else if ($user_response->result == 1) {
                $game->user_response = "true";
                //$game->points = 0;
            } else if ($user_response->result == 0) {
                $game->user_response = "false";
            }


            $game->items = $items;
        }

        $user = Auth::user();
        $user_id = $user->id;

        $topic = DB::table('lessons')->where('id', $lesson_id)->get('topic_id')->first();
        $topic_id = $topic->topic_id;

        $userSituation = UserSituation::where('user_id', $user_id)->where(function ($query) use ($lesson_id) {
            $query->where('lesson_id', $lesson_id)->where('level', '!=', 'challenge');
        })->get()->first();

        if ($userSituation == null) {
            $userSituation = "";
            $round = 0;
        } else {
            $round  = $userSituation->round;
        }

        $user_points = 0;

        foreach ($games as $game) {
            $game_id = $game->id;
            $user_points_round = GamePoint::where('game_id', $game_id)->where(function ($query) use ($user_id, $round) {
                $query->where('user_id', $user_id)->where('round', $round);
            })->get()->last();
            if ($user_points_round != null) {
                $user_points += $user_points_round->points;
            }
            $feedback_ko = $game->feedback_ko;
            $feedback_ok = $game->feedback_ok;
            if ($feedback_ko == null) {
                $game->feedback_ko = "";
            }
            if ($feedback_ok == null) {
                $game->feedback_ok = "";
            }
            if ($game->title == null) {
                $game->title = "";
            }
            if ($game->question == null) {
                $game->question = "";
            }
            if ($game->images_ref == null) {
                $game->images_ref = "";
            }
        }

        return response()->json(["lesson" => $games, "round" => $round, "user_points_round" => $user_points, "user_situation" => $userSituation],  200);
    }

    public function isGameCompleted($game_id)
    {
        $user = Auth::user();

        $game_user = DB::table('game_points')->where('user_id', $user->id)->where('game_id', $game_id)->get()->first();

        if ($game_user != null) {
            $res = 'true';
        } else {
            $res = 'false';
        }

        return response()->json(["game_completed" => $res],  200);
    }

    public function getGames()
    {
        $user = Auth::user();

        $games = Game::all();

        return response()->json(["games" => $games],  200);
    }
        /**
     * Función a la que se llama cuando el usuario responde a una pregunta.
     * Devuelve answer.
     * true, si la respuesta es correcta.
     * false, si la respuesta es incorrecta.
     * already_asnwer, si la respuesta es correcta y ya la había respondido bien antes.
     */
    public function gameResponse(Request $request)
    {
        // validator
        $validator = Validator::make($request->all(), [
            'game_id' => 'required',
            'user_answers' => 'required',
            'same_round' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        // if same_round = 1 means the user is going to repeat the same answer so i have to reset the situation and delete the previous answer
        $same_round = false;
        if($request->has('same_round')){
            if($request['same_round'] == 1){
                $same_round = true;
            }
        }

        // get the user
        $user = Auth::user();
        $user_id = $user->id;

        $user_answer = UserApp::where('id',$user_id)->select('points','state','status')->get()->first();

        // find the game
        $game_id = $request['game_id'];
        $game = Game::findOrFail($game_id);

        // set the situation of the user
        $lesson_id = $game->lesson_id;
        $topic = DB::table('lessons')->where('id', $lesson_id)->get('topic_id')->first();
        $topic_id = $topic->topic_id;
        $game_level = $game->level;

        // set user situation
        $user_answer->setUserSituation($game_level, $user_id, $topic_id,$game,$lesson_id,$same_round);

        if($same_round == true){
            $result = "false";

            // delete the results for that game (last round)
            $userResponse_exist = UserResponse::where('user_id', $user_id)->where('game_id', $game_id)->get()->first();
            if($userResponse_exist != null){
                $userResponse_exist->delete();
            }

            $exist_last = GamePoint::where('game_id', $game_id)->where('user_id', $user_id)->get()->last();
            if($exist_last != null){
                $exist_last->delete();
            }

        }else{
            $user_answers_array = $request['user_answers'];
            $user_answers = json_decode(json_encode($user_answers_array), true);

            // check if answers are correct
            if(!$this->checkAnswers($game_id,$user_answers,$user_id,$topic_id,$same_round)){
                return response()->json(["answer" => 'false', "user" => $user_answer],  200);
            }

            // asign the points (only if the answer is correct. If its not correct, the points assigment is done before in checkAnswers)
            $result = $this->assignPoints($game,$game_id,$user_id,$same_round);

            // save the response of that game
            $this->saveResponse($game_id,$user_id,$same_round);

        }

        $user_answer = UserApp::where('id',$user_id)->select('points','state','status')->get()->first();

        return response()->json(["answer" => $result, "user" => $user_answer],  200);
    } /* fin de game response */

    public function gamesFinished()
    {

        $user = Auth::user();
        $user_id = $user->id;
        $games_finished = DB::table('game_points')->where('user_id', $user_id)->get();

        return response()->json(["games_finished" => $games_finished],  200);
    }

    public function gamesFinishedByLesson()
    {

        $user = Auth::user();
        $user_id = $user->id;
        $games_finished = DB::table('game_points')->where('user_id', $user_id)->get();

        return response()->json(["games_finished" => $games_finished],  200);
    }

    public function gamesLeveltopic($topic_id, $level)
    {

        $user = Auth::user();
        $lessons = DB::table('lessons')->where('topic_id', $topic_id)->get();
        $result = false;
        $user_id = $user->id;
        foreach ($lessons as $lesson) {
            $lesson_id = $lesson->id;
            $games = Game::where('lesson_id', $lesson_id)->where('level', $level)->get('id');
            foreach ($games as $game) {

                $exist = GamePoint::where('game_id', $game->id)->where(function ($query) use ($user_id) {
                    $query->where('user_id', $user_id)->where('points', '!=', '0');
                })->get()->first();

                if ($exist == null) {
                    $result = false;
                    break;
                }
                $result = true;
            }
        }

        if ($result) {

            $achievement = Achievement::where('code', "level-$level-$topic_id")->get()->first();

            $exist = UserAchievement::where('achievement_id', $achievement->id)->where('user_id', $user->id)->get()->first();

            if ($exist == null) {
                $userAchievement = UserAchievement::create([
                    'user_id' => $user->id,
                    'achievement_id' => $achievement->id,
                    'points' => $achievement->points,
                ]);

                $user->points += $achievement->points;
                if ($user->save()) {
                    return response()->json(["achievement" => 'true'],  200);
                } else {
                    return response()->json(["achievement" => 'false'],  200);
                }
            }
        }
    }

    public function gamesLevelAll($level)
    {

        $user = Auth::user();
        $user_id = $user->id;
        $games = Game::where('level', $level)->get('id');

        $result = false;
        foreach ($games as $game) {

            $exist = GamePoint::where('id', $game->id)->where(function ($query) use ($user_id) {
                $query->where('user_id', $user_id)->where('points', '!=', '0');
            })->get()->first();

            if ($exist == null) {
                $result = false;
                break;
            }
            $result = true;
        }

        if ($result) {

            $achievement = Achievement::where('code', "level-$level.-all")->get()->first();

            $exist = UserAchievement::where('achievement_id', $achievement->id)->where('user_id', $user->id)->get()->first();

            if ($exist == null) {
                $userAchievement = UserAchievement::create([
                    'user_id' => $user->id,
                    'achievement_id' => $achievement->id,
                    'points' => $achievement->points,
                ]);

                $user->points += $achievement->points;
                if ($user->save()) {
                    return response()->json(["achievement" => 'true'],  200);
                } else {
                    return response()->json(["achievement" => 'false'],  200);
                }
            }
        }
    }

    public function gamesChallengeAll()
    {

        $user = Auth::user();
        $user_id = $user->id;
        $games = Game::where('level', 'challenge')->get('id');

        $result = false;
        foreach ($games as $game) {

            $exist = GamePoint::where('id', $game->id)->where(function ($query) use ($user_id) {
                $query->where('user_id', $user_id)->where('points', '!=', '0');
            })->get()->first();

            if ($exist == null) {
                $result = false;
                break;
            }
            $result = true;
        }

        if ($result) {

            $achievement = Achievement::where('code', "challenge-all")->get()->first();

            $exist = UserAchievement::where('achievement_id', $achievement->id)->where('user_id', $user->id)->get()->first();

            if ($exist == null) {
                $userAchievement = UserAchievement::create([
                    'user_id' => $user->id,
                    'achievement_id' => $achievement->id,
                    'points' => $achievement->points,
                ]);

                $user->points += $achievement->points;
                $user->save();
            }
        }
    }

    public function gameFirstChallenge($lesson_id)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $games = Game::where('lesson_id', $lesson_id)->where('level','challenge')->get();
        $res = false;
        foreach ($games as $game) {

            $game_user = GamePoint::where('id', $game->id)->where(function ($query) use ($user_id) {
                $query->where('user_id', $user_id)->where('points', '!=', '0');
            })->get()->first();

            if ($game_user == null) {
                break;
            } else {
                $res = true;
            }
        }

        if ($res) {
            $achievement = Achievement::where('code', "first-challenge")->get()->first();

            $exist = UserAchievement::where('achievement_id', $achievement->id)->where('user_id', $user->id)->get()->first();

            if ($exist == null) {
                $userAchievement = UserAchievement::create([
                    'user_id' => $user->id,
                    'achievement_id' => $achievement->id,
                    'points' => $achievement->points,
                ]);

                $user->points += $achievement->points;
                $user->save();
            }
        }
    }

    public function resetGamesInLesson($lesson_id)
    {

        $user = Auth::user();
        $user_id = $user->id;

        $games = DB::table('games')->where(function ($query) use ($lesson_id) {
            $query->where('lesson_id', $lesson_id)->where('level', '!=', 'challenge');
        })->orderBy('level')->get();

        foreach ($games as $game) {
            $user_response = DB::table('user_responses')->where('game_id', $game->id)->where('user_id', $user_id);

            $user_response->delete();
        }

        $user_situation = UserSituation::where('lesson_id', $lesson_id)->where(function ($query) use ($user_id) {
            $query->where('user_id', $user_id)->where('level', '!=', 'challenge');
        })->get()->last();
        if($user_situation != null){
            $round = ($user_situation->round) + 1;
            $user_situation->round = $round;
        }else{
            return response()->json(["resetGames" => 'false'], 500);
        }

        $user_situation->game_id = $games[0]->id;
        $user_situation->level = $games[0]->level;
        $user_situation->save();

        return response()->json(["resetGames" => 'true'],  200);
    }

    public function resetChallengeInTopic($topic_id)
    {

        $user = Auth::user();
        $user_id = $user->id;

        $lessons = DB::table('lessons')->where('topic_id', $topic_id)->get();

        foreach ($lessons as $lesson) {
            $lesson_id = $lesson->id;
            $games = DB::table('games')->where('lesson_id', $lesson_id)->where('level', 'challenge')->get();

            if (!($games->isEmpty())) {
                foreach ($games as $game) {
                    $user_response = DB::table('user_responses')->where('game_id', $game->id)->where('user_id', $user_id);

                    $user_response->delete();
                }
            } else {
                return response()->json(["resetGames" => 'false'],  200);
            }
        }

        $user_situation = UserSituation::where('topic_id', $topic_id)->where(function ($query) use ($user_id) {
            $query->where('user_id', $user_id)->where('level', 'challenge');
        })->get()->last();

        if ($user_situation != null) {
            $round = ($user_situation->round) + 1;
            $user_situation->round = $round;
            $user_situation->game_id = $games[0]->id;
            $user_situation->level = $games[0]->level;
            $user_situation->save();
        }

        return response()->json(["resetGames" => 'true'],  200);
    }

    public function getChallengeTopic($topic_id)
    {

        $user = Auth::user();
        $user_id = $user->id;

        $topic = Topic::find($topic_id);
        $lessons = $topic->lessons()->get();
        $games_array = array();
        foreach ($lessons as $lesson) {
            $games = $lesson->gamesChallenge();

            foreach ($games as $game) {
                $game_id = $game->id;
                $items_id = DB::table('itemin_games')->where('game_id', $game_id)->get('item_id');
                //$items = DB::table('itemin_games')->where('game_id',$game_id)->get();
                //$i = 0;
                $items = array();
                foreach ($items_id as $item_id) {
                    $answers_id = DB::table('answersin_items')->where('item_id', $item_id->item_id)->get('answer_id');
                    $item = DB::table('itemin_games')->where('item_id', $item_id->item_id)->get()->first();
                    $item_data = DB::table('items')->where('id', $item_id->item_id)->get()->first();
                    $item->type = $item_data->type;
                    $item->index = $item_data->index;
                    $item->value = $item_data->value;
                    $answers_array = array();
                    foreach ($answers_id as $answer_id) {
                        $answers = DB::table('answers')->where('id', $answer_id->answer_id)->get();
                        $answers_array[] = $answers;
                    }

                    $item->answers = $answers_array;

                    $items[] = $item;

                    //$i++;
                }

                $user_response = DB::table('user_responses')->where('user_id', $user_id)->where('game_id', $game_id)->get('result')->first();

                if ($user_response == null) {
                    $game->user_response = "";
                } else if ($user_response->result == 1) {
                    $game->user_response = "true";
                    //$game->points = 0;
                } else if ($user_response->result == 0) {
                    $game->user_response = "false";
                }

                $game->items = $items;
            }

            $userSituation = UserSituation::where('user_id', $user_id)->where(function ($query) use ($lesson) {
                $query->where('lesson_id', $lesson->id)->where('level', 'challenge');
            })->get()->first();

            if ($userSituation == null) {
                $userSituation = "";
                $round = 0;
            } else {
                $round  = $userSituation->round;
            }

            $user_points = 0;
            $num_challenges_finished = 0;
            $num_challenges_tot = count($games);

            foreach ($games as $game) {
                $game_id = $game->id;

                $user_points_round = GamePoint::where('game_id', $game_id)->where(function ($query) use ($user_id, $round) {
                    $query->where('user_id', $user_id)->where('round', $round);
                })->get()->last();
                if ($user_points_round != null) {
                    $user_points += $user_points_round->points;
                    $num_challenges_finished += 1;
                }
                $feedback_ko = $game->feedback_ko;
                $feedback_ok = $game->feedback_ok;
                if ($feedback_ko == null) {
                    $game->feedback_ko = "";
                }
                if ($feedback_ok == null) {
                    $game->feedback_ok = "";
                }
                if ($game->title == null) {
                    $game->title = "";
                }
                if ($game->question == null) {
                    $game->question = "";
                }
                if ($game->images_ref == null) {
                    $game->images_ref = "";
                }
            }

            $games_array[] = $games;
        }

        return response()->json(["topic_challenge" => $games_array, "round" => $round, "user_points_round" => $user_points, "user_situation" => $userSituation, "num_challenges_tot" => $num_challenges_tot, "num_challenges_finished" => $num_challenges_finished],  200);
    }

    public function checkAnswers($game_id,$user_answers,$user_id,$topic_id,$same_round){

        $itemsInGame = DB::table('itemin_games')->where('game_id', $game_id)->get('item_id');

        foreach ($itemsInGame as $item) {
            $item_id = $item->item_id;
            $answersInGame = DB::table('answersin_items')->where('item_id', $item_id)->get('answer_id');

            foreach ($answersInGame as $answer) {
                $answer_id = $answer->answer_id;
                $result = DB::table('answers')->where('id', $answer_id)->get()->first();
                if ($result->is_correct == 1) {
                    if (!in_array($answer_id, $user_answers)) {

                        $userResponse_exist = UserResponse::where('user_id', $user_id)->where('game_id', $game_id)->get()->first();
                        $game = Game::find($game_id);
                        $lesson_id = $game->lesson_id;
                        if ($userResponse_exist == null) {
                            $userResponse = UserResponse::create([
                                'user_id' => $user_id,
                                'game_id' => $game_id,
                                'lesson_id' => $lesson_id,
                                'result' => false,
                            ]);
                        }
                        $exist = GamePoint::where('game_id', $game_id)->where('user_id', $user_id)->get()->first();

                        if ($exist == null) {
                            // asign the points
                            $gamePoint = GamePoint::create([
                                'game_id' => $game_id,
                                'lesson_id' => $lesson_id,
                                'topic_id' => $topic_id,
                                'user_id' => $user_id,
                                'points' => 0,
                                'round' => 0,
                            ]);
                        } else {

                            $exist_last = GamePoint::where('game_id', $game_id)->where('user_id', $user_id)->get()->last();
                            $game = Game::find($game_id);
                        $lesson_id = $game->lesson_id;

                            //differentiate if user is answering inside same round
                            if(!$same_round){
                                $gamePoint = GamePoint::create([
                                    'game_id' => $game_id,
                                    'topic_id' => $topic_id,
                                    'lesson_id' => $lesson_id,
                                    'user_id' => $user_id,
                                    'points' => 0,
                                    'round' => ($exist_last->round) + 1,
                                ]);
                            }


                        }

                        return false;

                    }
                } else {

                    if (in_array($answer_id, $user_answers)) {
                        $userResponse_exist = UserResponse::where('user_id', $user_id)->where('game_id', $game_id)->get()->first();
                        $game = Game::find($game_id);
                        $lesson_id = $game->lesson_id;
                        if ($userResponse_exist == null) {
                            $userResponse = UserResponse::create([
                                'user_id' => $user_id,
                                'game_id' => $game_id,
                                'lesson_id' => $lesson_id,
                                'result' => false,
                            ]);
                        }
                        $exist = GamePoint::where('game_id', $game_id)->where('user_id', $user_id)->get()->first();

                        if ($exist == null) {
                            // asign the points
                            $gamePoint = GamePoint::create([
                                'game_id' => $game_id,
                                'topic_id' => $topic_id,
                                'lesson_id' => $lesson_id,
                                'user_id' => $user_id,
                                'points' => 0,
                                'round' => 0,
                            ]);
                        } else {
                            $exist_last = GamePoint::where('game_id', $game_id)->where('user_id', $user_id)->get()->last();
                            $game = Game::find($game_id);
                        $lesson_id = $game->lesson_id;

                        if(!$same_round){
                            $gamePoint = GamePoint::create([
                                'game_id' => $game_id,
                                'topic_id' => $topic_id,
                                'lesson_id' => $lesson_id,
                                'user_id' => $user_id,
                                'points' => 0,
                                'round' => ($exist_last->round) + 1,
                            ]);
                        }

                        }

                        return false;

                    }
                }
            }
        }
        return true;
    }

    public function assignPoints($game,$game_id,$user_id,$same_round){
        $points = $game->points;
        $lesson_id = $game->lesson_id;
        $topic_id = (DB::table('lessons')->where('id', $lesson_id)->get()->first())->topic_id;
        // check if the user has already answer that game
        $level_game = $game->level;
        if($level_game != "challenge"){

            $user_situation = UserSituation::where('user_id',$user_id)->where(function ($query) use ($lesson_id) {
                $query->where('lesson_id',$lesson_id)->where('level',"!=", "challenge");
            })->get()->first();

        }else{
            $user_situation = UserSituation::where('user_id',$user_id)->where(function ($query) use ($lesson_id) {
                $query->where('lesson_id',$lesson_id)->where('level', "challenge");
            })->get()->first();
        }




        if($user_situation != null){
            $user_situation_round = $user_situation->round;
        }else{
            $user_situation_round = 0;
        }

        $exist = GamePoint::where('game_id', $game->id)->where(function ($query) use ($user_id,$user_situation_round) {
            $query->where('user_id', $user_id)->where('round',$user_situation_round);
        })->get()->first();

        $exist_already_answer = GamePoint::where('game_id', $game->id)->where(function ($query) use ($user_id,$user_situation_round) {
            $query->where('user_id', $user_id)->where('points',10);
        })->get()->first();

        if ($exist == null && $exist_already_answer == null) {

            // asign the points
            $gamePoint = GamePoint::create([
                'game_id' => $game_id,
                'topic_id' => $topic_id,
                'lesson_id' => $lesson_id,
                'user_id' => $user_id,
                'points' => $points,
                'round' => $user_situation_round,
            ]);

            // asign the points for this game
            $user = UserApp::where('id', $user_id)->get()->first();
            $points_user = $user->points;
            $user->points = $points + $points_user;
            // recalculate the status
            $user->status = $user->getUserStatus();
            $user->save();

            $result = 'true';

            // ACHIEVEMENTS

            // check if its the first game answered
            $this->setFirstCorrectAnswerAchievement($user_id,$user);

            $this->gamesLeveltopic($topic_id,$game->level);
            $this->gamesLevelAll($game->level);
            $this->gameFirstChallenge($game->lesson_id);
            $this->gamesChallengeAll();

        } else {

            // select the last game_point to get the round
            $exist_last = GamePoint::where('game_id', $game_id)->where('user_id', $user_id)->get()->last();
            $game = Game::find($game_id);
            $lesson_id = $game->lesson_id;

            if(!$same_round){
                $gamePoint = GamePoint::create([
                    'game_id' => $game_id,
                    'topic_id' => $topic_id,
                    'lesson_id' => $lesson_id,
                    'user_id' => $user_id,
                    'points' => 0,
                    'round' => ($exist_last->round) + 1,
                ]);
                $result = 'already answered';
            }else{
                // asign the points
                $exist_last->points = $points;
                $exist_last->save();

            // asign the points for this game
            $user = UserApp::where('id', $user_id)->get()->first();
            $user->points += $points;
            // recalculate the status
            $user->status = $user->getUserStatus();
            $user->save();

            $result = 'true';

            // ACHIEVEMENTS

            // check if its the first game answered
            $this->setFirstCorrectAnswerAchievement($user_id,$user);

            $this->gamesLeveltopic($topic_id,$game->level);
            $this->gamesLevelAll($game->level);
            $this->gameFirstChallenge($game->lesson_id);
            $this->gamesChallengeAll();
            }


        }

        return $result;
    }

    public function saveResponse($game_id,$user_id,$same_round){
        $userResponse_exist = UserResponse::where('game_id', $game_id)->where('user_id', $user_id)->get()->first();
        $game = Game::find($game_id);
        $lesson_id = $game->lesson_id;
        if ($userResponse_exist == null) {
            $userResponse = UserResponse::create([
                'user_id' => $user_id,
                'game_id' => $game_id,
                'lesson_id' => $lesson_id,
                'result' => true,
            ]);
        }else if($same_round){
            $userResponse_exist->result = true;
            $userResponse_exist->save();
        }
    }

    public function setFirstCorrectAnswerAchievement($user_id,$user){
        $game_points = GamePoint::where('user_id', $user_id)->where('points',"!=",0)->get();
        $num_game_points = count($game_points);
        if ($num_game_points == 1) {
            $achievement = Achievement::where('code', 'first-test')->get()->first();
            $exist = UserAchievement::where('achievement_id', $achievement->id)->where('user_id', $user_id)->get()->first();

            if ($exist == null) {
                $userAchievement = UserAchievement::create([
                    'user_id' => $user_id,
                    'achievement_id' => $achievement->id,
                    'points' => $achievement->points,
                ]);

                $user->points += $achievement->points;
                $user->save();
            }
        }

    }

    public function userAchievementsPoints(){

        $user = Auth::user();
        $user_achi_points = UserAchievement::where('user_id',$user->id)->sum('points');
        return response()->json(["achievement_point" => $user_achi_points],  200);

    }

}

