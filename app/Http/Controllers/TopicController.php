<?php

namespace App\Http\Controllers;

use App\Achievement;
use App\TimeinLesson;
use App\Topic;
use Illuminate\Http\Request;
use Response;
use Validator;
use Illuminate\Support\Facades\Redirect;
use SebastianBergmann\Timer\Timer;

class TopicController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $topics = Topic::sortable()->paginate(20);
        return view('topics/index',['topics' => $topics]);
    }

    public function getJson($id)
    {
        $topic = Topic::find($id);

        return Response::json(array('success' => true, 'data' => $topic), 200);
    }

    public function create(Request $request){
        $data = $request->all();

        $validator = $this->validator($request->all());
        // Validate the input and return correct response
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator->errors());
        }


        try{
            $topic = Topic::create([
                'name' => $data['name'],
                'points' => 0,
            ]);
            $nums = range(1,3);
            foreach($nums as $num){
                Achievement::create([
                    'title'=>'Achievement level '.$num,
                    'description'=>'Finishing all the games from level '.$num.' in '.$topic->name,
                    'code'=>'level-'.$num.'-'.$topic->id,
                ]);
            }
            $msg = "The topic was successfully created.";
            $type_msg = "success";
        }
        catch(\Exception $e){

            $msg = "Oops. There was an error and the topic couldn't be created";
            $type_msg = "danger";

        }
        $topics = Topic::sortable()->paginate(20);

        //return Redirect::route('topic',['topics' => $topics, 'msg' => $msg, 'type_msg' => $type_msg]);
        return view('topics/index',['topics' => $topics, 'msg' => $msg, 'type_msg' => $type_msg]);

    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:40','unique:topics'],

        ]);
    }

    public function delete(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'topic_id' => ['required', 'int', 'exists:topics,id'],
        ]);
        // Validate the input and return correct response
        if ($validator->fails()) {
            return Response::json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        // only delete of it doesn't ahve any content, lessons or games

        $topic = Topic::find($request->get('topic_id'));

        if($topic->content()->count() != 0 || $topic->lessons()->count() != 0 || $topic->content()->count() != 0){
            return Response::json(array('success' => false), 400);
        }else{
            $nums = range(1,3);
            foreach($nums as $num){
                Achievement::where('code','level-'.$num.'-'.$topic->id)->delete();
            }
            $topic->delete();
            return Response::json(array('success' => true), 200);
        }


    }

    public function update(Request $request, $id)
    {
        $topic = Topic::find($id);
        if (!$topic) {
            return Response::json(404);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:40'],

        ]);
        // Validate the input and return correct response
        if ($validator->fails()) {
            return Response::json(array(
                'success' => false,
                'data' => $request->all(),
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        }

            $topic->name = $request->get('name');
            $topic->save();


        return Response::json(array('success' => true, 'data' => $request->all()), 200);
    }



}
