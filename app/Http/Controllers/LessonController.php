<?php

namespace App\Http\Controllers;

use App\Lesson;
use App\Topic;
use Illuminate\Http\Request;
use Response;
use Validator;
use Illuminate\Support\Facades\Redirect;

class LessonController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        
        $lessons = Lesson::sortable()->paginate(20);
        
        $topics = Topic::sortable()->paginate(20);

        return view('lessons/index',['lessons' => $lessons, 'topics' => $topics]);
    }

    public function getJson($id)
    {
        $lesson = Lesson::find($id);
        
        return Response::json(array('success' => true, 'data' => $lesson), 200);
    }


    public function create(Request $request){
        $data = $request->all();
        
        $validator = $this->validator($request->all());
        // Validate the input and return correct response
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator->errors());
        }

        $topics = Topic::all();

        try{
            $lesson = Lesson::create([
                'name' => $data['name'],
                'topic_id' => $data['topic_id'],
                'points'=> 0,
            ]);
            
            $msg = "The lesson was successfully created.";
            $type_msg = "success";
        }
        catch(\Exception $e){
            
            $msg = "Oops. There was an error and the lesson couldn't be created";
            $type_msg = "danger";
            
        }
        $lessons = Lesson::sortable()->paginate(20);
        
        $topics = Topic::sortable()->paginate(20);
        
        //return Redirect::route('lesson',['lessons' => $lessons, 'topics' => $topics, 'msg' => $msg, 'type_msg' => $type_msg]);
        return view('lessons/index',['lessons' => $lessons, 'topics' => $topics, 'msg' => $msg, 'type_msg' => $type_msg]);
        
        
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:45'],
            'topic_id' => ['required', 'int'],
        ]);
    }

    public function delete(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'lesson_id' => ['required', 'int', 'exists:lessons,id'],
        ]);
        // Validate the input and return correct response
        if ($validator->fails()) {
            return Response::json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        
        $lesson = Lesson::find($request->get('lesson_id'));

        if($lesson->games()->count() != 0){
            return Response::json(array('success' => false), 400);
        }else{
            $lesson->delete();
            return Response::json(array('success' => true), 200);
        }
    }

    public function update(Request $request, $id)
    {
        $lesson = Lesson::find($id);
        if (!$lesson) {
            return Response::json(404);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:45'],
        ]);
        // Validate the input and return correct response
        if ($validator->fails()) {
            return Response::json(array(
                'success' => false,
                'data' => $request->all(),
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        if ($request->exists('name')) {
            $lesson->name = $request->get('name');
        }


        $lesson->save();

        $lessons = Lesson::sortable()->paginate(20);
        
        $topics = Topic::sortable()->paginate(20);

        
        return Response::json(array('success' => true, 'data' => $request->all()), 200);
    }

    public function getLessons(Request $request){

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
        $topic_id = $request->get('topic_id');

        $lessons = Lesson::where('topic_id',$topic_id)->get('id');

        return Response::json(array('success' => true, 'data' => $lessons), 200);

    }



}
