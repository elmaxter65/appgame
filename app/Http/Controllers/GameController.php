<?php

namespace App\Http\Controllers;

use App\Game;
use App\Item;
use Response;
use App\Topic;
use Validator;
use App\Answer;
use App\Lesson;
use App\UserApp;
use App\GamePoint;
use App\IteminGame;
use App\UserResponse;
use App\AnswersinItem;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Redirect;

class GameController extends Controller
{

    use UploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
       

        $games = Game::all();
        $topics = Topic::all();
        $lessons = Lesson::all();
 
        return view('games/index',['games' => $games, 'topics' => $topics, 'lessons' => $lessons]);
    }

    public function getJson($id)
    {
        $game = Game::find($id);
        
        return Response::json(array('success' => true, 'data' => $game), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = $request->all();
        
        $validator = $this->validator($request->all());
        // Validate the input and return correct response
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator->errors());
        }


        try{
            $game = Game::create([
                'lesson_id' => $data['lesson_id'],
                'level' => $data['level'],
                'points' => $data['points'],
                'dynamic_number' => $data['dynamic_number'],
                'title' => $data['title'],
                'question' => $data['question'],
                'images_ref' => $data['images_ref']
            ]);
            
            $msg = "The game was successfully created.";
            $type_msg = "success";
        }

        catch(\Exception $e){
            
            $msg = "Oops. There was an error and the game couldn't be created";
            $type_msg = "danger";
            
        }

        // add the points to the lesson and then the points to the topic

        $lesson_id = $game->lesson_id;
        $lesson = Lesson::where('id',$lesson_id)->get()->first();
        if($lesson != null){
            $lesson_points = $lesson->points + $game->points;
            $lesson->points = $lesson_points;
            $lesson->save();
        }
        

        $topic = Topic::where('id',$lesson->topic_id)->get()->first();
        if($topic != null){
            $topic_points = $topic->points + $game->points;
            $topic->points = $topic_points;
            $topic->save();
        }
        
        $games = Game::all();
        $topics = Topic::all();
        $lessons = Lesson::all();
 
            
        return view('games/index',['games' => $games, 'topics' => $topics, 'lessons' => $lessons, 'msg' => $msg, 'type_msg' => $type_msg]);

        
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'level' => ['required'],
            'lesson_id' => ['required','numeric'],
            'points' => ['required', 'numeric'],
            'dynamic_number' => ['required', 'numeric'],
            'title' => ['required'],
        ]);
    }
    

    public function showContent($id){

        // if the game has content, we don't allow the user to see this view

        $game = Game::find($id);
        $dynamic_number = Game::find($id)->dynamic_number;

        $game_content = DB::table('itemin_games')->where('game_id',$id)->get()->first();

        if($game_content == null){
            return view("games/dynamics/dynamic$dynamic_number",['game' => $game]);
        }else{

            $games = Game::all();
            $topics = Topic::all();
            $lessons = Lesson::all();
 
            return view('games/index',['games' => $games, 'topics' => $topics, 'lessons' => $lessons,'errorContent'=> true]);
           
        }

        

    }

    public function addContent(Request $request){

        $data = $request->all();
        $dynamic_number = $data['dynamic_number'];

        switch($dynamic_number){

            case '0': 
            // feedback
            $game_id = $data['game_id'];
            if($data['feedback_ok'] != null){
                $game = Game::findOrFail($game_id);
                $game->feedback_ok = $data['feedback_ok'];
                $game->save();
            }

            if($data['feedback_ko'] != null){
                $game = Game::findOrFail($game_id);
                $game->feedback_ko = $data['feedback_ko'];
                $game->save();
            }

            $item = Item::create([
                'type' => 'text',
                'index' => 0,
                'value' => "",
            ]);

            $item_id = $item->id;

            $itemInGame = IteminGame::create([
                'game_id' => $data['game_id'],
                'item_id' => $item_id,
            ]);
            $count_true = 0;
            for($i=1;$i<(count($request->all()));$i++){
                if(($request->file("image$i")) != null){

            // item = image
            $validator = Validator::make($request->all(), [
                "image$i" => ['required','image','mimes:jpeg,png,jpg,gif','max:2048'],
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withInput();
                }

                


                // answers

                if(($request->has("correct_answer$i"))){
                    
                    $isCorrect = true;
                    $percentage = 1;
                    $count_true ++;

                }else{
          
                    $isCorrect = false;
                    $percentage = 0;

                }

                $answer = Answer::create([
                    'value' => $this->getImagePath($request->file("image$i"),$request->get('game_id'),"1".($i-1)),
                    'is_correct' => $isCorrect,
                    'percentage' => $percentage,
                ]);

                $answer_id = $answer->id;

                $answerInItem = AnswersinItem::create([
                    'answer_id' => $answer_id,
                    'item_id' => $item_id,
                ]);


            }
        }

        $answers_id = DB::table('answersin_items')->where('item_id',$item_id)->get('answer_id');
        foreach($answers_id as $answer_id){
            $answer = Answer::find($answer_id->answer_id);
            if($answer->is_correct == 1){
                if($count_true !=0){
                    $answer->percentage = 1/$count_true;
                }
                
                $answer->save();
            }
            
        }


            break;

            case '2': 
            // feedback
            $game_id = $data['game_id'];
            if($data['feedback_ok'] != null){
                $game = Game::findOrFail($game_id);
                $game->feedback_ok = $data['feedback_ok'];
                $game->save();
            }

            if($data['feedback_ko'] != null){
                $game = Game::findOrFail($game_id);
                $game->feedback_ko = $data['feedback_ko'];
                $game->save();
            }


            // item = image
            $validator = Validator::make($request->all(), [
                'image' => ['required','mimes:jpeg,png,jpg,gif,mp4','max:25000'],
                'correct_answer' => ['required'],
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                if($request->file('image')->getClientOriginalExtension() == "mp4"){
                    $type = "video";
                }else{
                    $type = "image";
                }

                $item = Item::create([
                    'type' => $type,
                    'index' => 0,
                    'value' => $this->getImagePath($request->file('image'),$request->get('game_id'),1),
                ]);

                $item_id = $item->id;

                $itemInGame = IteminGame::create([
                    'game_id' => $data['game_id'],
                    'item_id' => $item_id,
                ]);

                $correct_answer = $data['correct_answer'];
                if($correct_answer == 1){
                    $valueAnswer = "yes";
                    $isCorrect = true;
                    $percentage = 1;

                    $valueAnswer_2 = "no";
                    $isCorrect_2 = false;
                    $percentage_2 = 0;
                }else{
                    $valueAnswer = "no";
                    $isCorrect = true;
                    $percentage = 1;

                    $valueAnswer_2 = "yes";
                    $isCorrect_2 = false;
                    $percentage_2 = 0;
                }

            // answers true or false
                $answer = Answer::create([
                    'value' => $valueAnswer,
                    'is_correct' => $isCorrect,
                    'percentage' => $percentage,
                ]);

                $answer_id = $answer->id;

                $answerInItem = AnswersinItem::create([
                    'answer_id' => $answer_id,
                    'item_id' => $item_id,
                ]);

                $answer_2 = Answer::create([
                    'value' => $valueAnswer_2,
                    'is_correct' => $isCorrect_2,
                    'percentage' => $percentage_2,
                ]);

                $answer_id_2 = $answer_2->id;

                $answerInItem_2 = AnswersinItem::create([
                    'answer_id' => $answer_id_2,
                    'item_id' => $item_id,
                ]);

            break;
            
            
                case '3': 
                // feedback
        $game_id = $data['game_id'];
        if($data['feedback_ok'] != null){
                    $game = Game::findOrFail($game_id);
                    $game->feedback_ok = $data['feedback_ok'];
                    $game->save();
        }
    
        if($data['feedback_ko'] != null){
                    $game = Game::findOrFail($game_id);
                    $game->feedback_ko = $data['feedback_ko'];
                    $game->save();
        }
    
    
                // item = image
        $validator = Validator::make($request->all(), [
                    'image' => ['required','mimes:jpeg,png,jpg,gif,mp4','max:25000'],
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withInput();
        }
        if($request->file('image')->getClientOriginalExtension() == "mp4"){
            $type = "video";
        }else{
            $type = "image";
        }
    
        $item = Item::create([
            'type' => $type,
            'index' => 0,
            'value' => $this->getImagePath($request->file('image'),$request->get('game_id'),1),
        ]);
    
        $item_id = $item->id;
    
        $itemInGame = IteminGame::create([
            'game_id' => $data['game_id'],
            'item_id' => $item_id,
        ]);
    
        // multiple answers
        $count = 0;
        for($i=1;$i<(count($request->all()));$i++){
            if(($request->get("answer$i")) != null){
                
                if(($request->get("correct_answer$i")) != null){
                    $answer = Answer::create([
                        'value' => $request->get("answer$i"),
                        'is_correct' => true,
                        'percentage' => 0,
                    ]);
                    $count++;
                    $answer_id = $answer->id;
                    $answers_id_array [] = $answer_id;

                }else{
                    $answer = Answer::create([
                        'value' => $request->get("answer$i"),
                        'is_correct' => false,
                        'percentage' => 0,
                    ]);
                    $answer_id = $answer->id;
                }

                
                $answerInItem = AnswersinItem::create([
                        'answer_id' => $answer_id,
                        'item_id' => $item_id,
                ]);

                

            }

        }

        foreach($answers_id_array as $answers_id){
            $answer = Answer::find($answers_id);
            $answer->percentage = 1/$count;
            $answer->save();
        }

        break;

        case '4': 

                        // feedback
                $game_id = $data['game_id'];
                if($data['feedback_ok'] != null){
                            $game = Game::findOrFail($game_id);
                            $game->feedback_ok = $data['feedback_ok'];
                            $game->save();
                }
            
                if($data['feedback_ko'] != null){
                            $game = Game::findOrFail($game_id);
                            $game->feedback_ko = $data['feedback_ko'];
                            $game->save();
                }
            
            
                $item = Item::create([
                    'type' => 'text',
                    'index' => 0,
                    'value' => '',
                ]);
            
                $item_id = $item->id;
            
                $itemInGame = IteminGame::create([
                    'game_id' => $data['game_id'],
                    'item_id' => $item_id,
                ]);
            
                // multiple answers
                $count = 0;
                $answers_id_array = array();
                for($i=1;$i<(count($request->all()));$i++){
                    if(($request->get("answer$i")) != null){
                        
                        if(($request->get("correct_answer$i")) != null){
                            $answer = Answer::create([
                                'value' => $request->get("answer$i"),
                                'is_correct' => true,
                                'percentage' => 0,
                            ]);
                            $count++;
                            $answer_id = $answer->id;
                            $answers_id_array [] = $answer_id;
    
                        }else{
                            $answer = Answer::create([
                                'value' => $request->get("answer$i"),
                                'is_correct' => false,
                                'percentage' => 0,
                            ]);
                            $answer_id = $answer->id;
                        }


                        $answerInItem = AnswersinItem::create([
                                'answer_id' => $answer_id,
                                'item_id' => $item_id,
                        ]);

                    }

                    

                }
                foreach($answers_id_array as $answers_id){
                    $answer = Answer::find($answers_id);
                    $answer->percentage = 1/$count;
                    $answer->save();
                }

                break;

                case '5': 
                // feedback
                $game_id = $data['game_id'];
                if($data['feedback_ok'] != null){
                    $game = Game::findOrFail($game_id);
                    $game->feedback_ok = $data['feedback_ok'];
                    $game->save();
                }

                if($data['feedback_ko'] != null){
                    $game = Game::findOrFail($game_id);
                    $game->feedback_ko = $data['feedback_ko'];
                    $game->save();
                }

                // item = image
                $validator = Validator::make($request->all(), [
                    'image' => ['required','image','mimes:jpeg,png,jpg,gif','max:2048'],
                    ]);

                    if ($validator->fails()) {
                        return redirect()->back()->withInput();
                    }

                    $item = Item::create([
                        'type' => 'image',
                        'index' => 0,
                        'value' => $this->getImagePath($request->file('image'),$request->get('game_id'),1),
                    ]);

                    $item_id = $item->id;
            
                    $itemInGame = IteminGame::create([
                    'game_id' => $data['game_id'],
                    'item_id' => $item_id,
                    ]);
                    
                    $count = 1;
                    for($i=1;$i<(count($request->all()));$i++){
                        if(($request->get("answer$i")) != null){

                            $item = Item::create([
                                'type' => 'dragBox',
                                'index' => $count,
                                'value' => "",
                            ]);

                            $item_id = $item->id;
            
                            $itemInGame = IteminGame::create([
                            'game_id' => $data['game_id'],
                            'item_id' => $item_id,
                            ]);
                    

                            // answers
                            $answer = Answer::create([
                                'value' => $request->get("answer$i"),
                                'is_correct' => true,
                                'percentage' => 1,
                            ]);

                            $answer_id = $answer->id;

                            $answerInItem = AnswersinItem::create([
                                'answer_id' => $answer_id,
                                'item_id' => $item_id,
                            ]);

                            $answer_2 = Answer::create([
                                'value' => "",
                                'is_correct' => false,
                                'percentage' => 0,
                            ]);

                            $answer_id_2 = $answer_2->id;

                            $answerInItem_2 = AnswersinItem::create([
                                'answer_id' => $answer_id_2,
                                'item_id' => $item_id,
                            ]);
                            
                            $count++;
                        }
                    }



                break;

                case '6': 
                // feedback
                $game_id = $data['game_id'];
                if($data['feedback_ok'] != null){
                    $game = Game::findOrFail($game_id);
                    $game->feedback_ok = $data['feedback_ok'];
                    $game->save();
                }
    
                if($data['feedback_ko'] != null){
                    $game = Game::findOrFail($game_id);
                    $game->feedback_ko = $data['feedback_ko'];
                    $game->save();
                }

                for($i=1;$i<(count($request->all()));$i++){
                    if(($request->file("image$i")) != null){

                    // item = image
                    $validator = Validator::make($request->all(), [
                        "image$i" => ['required','image','mimes:jpeg,png,jpg,gif','max:2048'],
                        ]);

                        if ($validator->fails()) {
                            return redirect()->back()->withInput();
                        }


                        $item = Item::create([
                            'type' => 'image',
                            'index' => ($i -1),
                            'value' => $this->getImagePath($request->file("image$i"),$request->get('game_id'),"1".($i-1)),
                        ]);
                        $item_id = $item->id;
            
                            $itemInGame = IteminGame::create([
                                'game_id' => $data['game_id'],
                                'item_id' => $item_id,
                            ]);
                        if(($request->get("text$i")) != null){
                            $answer = Answer::create([
                                'value' => $request->get("text$i"),
                                'is_correct' => true,
                                'percentage' => 1,
                            ]);

                            $answer_id = $answer->id;

                            $answerInItem = AnswersinItem::create([
                                'answer_id' => $answer_id,
                                'item_id' => $item_id,
                            ]);

                            $answer_2 = Answer::create([
                                'value' => "",
                                'is_correct' => false,
                                'percentage' => 0,
                            ]);

                            $answer_id_2 = $answer_2->id;

                            $answerInItem_2 = AnswersinItem::create([
                                'answer_id' => $answer_id_2,
                                'item_id' => $item_id,
                            ]);


                        }

                    }
                    

                }
  
                break;

                case '8': 
                // feedback
                $game_id = $data['game_id'];
                if($data['feedback_ok'] != null){
                    $game = Game::findOrFail($game_id);
                    $game->feedback_ok = $data['feedback_ok'];
                    $game->save();
                }

                if($data['feedback_ko'] != null){
                    $game = Game::findOrFail($game_id);
                    $game->feedback_ko = $data['feedback_ko'];
                    $game->save();
                }
                $count = 0;
                for($i=1;$i<=(count($request->all()));$i++){
                if(($request->get("textBefore$i")) != null || ($request->get("textAfter$i")) != null){
                    if(empty($request->get("textBefore$i"))){
                        $textBefore = "";
                    }else{
                        $textBefore = $request->get("textBefore$i");
                    }

                    if(empty($request->get("textAfter$i"))){
                        $textAfter = "";
                    }else{
                        $textAfter = $request->get("textAfter$i");
                    }
                   

                $itemBefore = Item::create([
                    'type' => 'textBefore',
                    'index' => $count,
                    'value' => $textBefore,
                ]);
                $item_id = $itemBefore->id;
            
                $itemInGame = IteminGame::create([
                    'game_id' => $data['game_id'],
                    'item_id' => $item_id,
                ]);
                $count++;

                $itemSelect = Item::create([
                    'type' => 'textSelect',
                    'index' => $count,
                    'value' => "",
                ]);

                $item_id = $itemSelect->id;
            
                $itemInGame = IteminGame::create([
                    'game_id' => $data['game_id'],
                    'item_id' => $item_id,
                ]);
                                //answers
                                for($j=1;$j<(count($request->all()));$j++){
                                    if(($request->get("answer$i-$j")) != null){
                                        if(($request->get("correct_answer$i-$j")) != null){
                                            $is_correct = true;
                                            $percentage = 1;
                                        }else{
                                            $is_correct = false;
                                            $percentage = 0;
                                        }
                                        $answer = Answer::create([
                                            'value' => $request->get("answer$i-$j"),
                                            'is_correct' => $is_correct,
                                            'percentage' => $percentage,
                                        ]);
                
                                        $answer_id = $answer->id;
                
                                        $answerInItem = AnswersinItem::create([
                                            'answer_id' => $answer_id,
                                            'item_id' => $item_id,
                                        ]);
                
                
                
                                    }
                                
                                }

                $count++;
                $itemAfter = Item::create([
                    'type' => 'textAfter',
                    'index' => $count,
                    'value' => $textAfter,
                ]);

                $item_id = $itemAfter->id;
            
                $itemInGame = IteminGame::create([
                    'game_id' => $data['game_id'],
                    'item_id' => $item_id,
                ]);
                $count++;

                }
            }


                break;

        }


        $games = Game::all();
        $topics = Topic::all();
        $lessons = Lesson::all();
        
        //return view('games/index',['games' => $games, 'topics' => $topics, 'lessons' => $lessons]);
        return redirect()->route('game', ['games' => $games, 'topics' => $topics, 'lessons' => $lessons]);
    }

    public function getImagePath($image,$game_id,$num_image){

        $name = $game_id."_".$num_image."_".time();
        $folder = 'games/';
        $filePath = $name. '.' . $image->getClientOriginalExtension();
        $this->uploadOne($image, $folder, 'public', $name);

        return $filePath;

    }

    public function update(Request $request, $id)
    {

        $game = Game::find($id);
        if (!$game) {
            return Response::json(404);
        }

        // substract the actual points of the game in the lesson and in the topic. later we will add the new value
        $lesson_id = $game->lesson_id;
        $lesson = Lesson::where('id',$lesson_id)->get()->first();
        $lesson_points = $lesson->points - $game->points;
        $lesson->points = $lesson_points;
        $lesson->save();

        $topic = Topic::where('id',$lesson->topic_id)->get()->first();
        $topic_points = $topic->points - $game->points;
        $topic->points = $topic_points;
        $topic->save();


        $validator = Validator::make($request->all(), [
            'points' => ['required', 'numeric'],
        ]);
        // Validate the input and return correct response
        if ($validator->fails()) {
            return Response::json(array(
                'success' => false,
                'data' => $request->all(),
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        if ($request->exists('points')) {
            $game->points = $request->get('points');
        }
        $game->save();


        // add the points to the lesson and then the points to the topic
        $lesson_id = $game->lesson_id;
        $lesson = Lesson::where('id',$lesson_id)->get()->first();
        $lesson_points = $lesson->points + $game->points;
        $lesson->points = $lesson_points;
        $lesson->save();

        $topic = Topic::where('id',$lesson->topic_id)->get()->first();
        $topic_points = $topic->points + $game->points;
        $topic->points = $topic_points;
        $topic->save();


        return Response::json(array('success' => true, 'data' => $request->all()), 200);
    }


    public function delete(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'game_id' => ['required', 'int', 'exists:games,id'],
        ]);
        // Validate the input and return correct response
        if ($validator->fails()) {
            return Response::json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        }
        
        $game_id = $request->get('game_id');

        $itemsInGame = DB::table('itemin_games')->where('game_id',$game_id)->get();

        foreach($itemsInGame as $itemInGame){
            $item_id = $itemInGame->item_id;

            Item::findOrFail($item_id)->delete();

            $answersInItem = DB::table('answersin_items')->where('item_id',$item_id)->get();

            foreach($answersInItem as $answerInItem){
                $answer_id = $answerInItem->answer_id;
                Answer::findOrFail($answer_id)->delete();
                AnswersinItem::findOrFail($answerInItem->id)->delete();
            }

            IteminGame::findOrFail($itemInGame->id)->delete();



        }

        
        Game::find($game_id)->delete();

        // substract the points to the users
        $game_points = GamePoint::where('game_id',$game_id)->get();
            foreach($game_points as $points){
                $user_id = $points->user_id;
                $points = $points->points;
                $user = UserApp::where('id',$user_id)->get()->first();
                $user_points = $user->points;
                $user->points = $user_points - $points;
                $user->save();
            }
        GamePoint::where('game_id',$game_id)->delete();
        $user_responses = UserResponse::where('game_id',$game_id)->delete();
        

        return Response::json(array('success' => true), 200);
    }

    

 
}
