<?php

namespace App\Http\Controllers;

use App\Topic;
use App\Lesson;
use App\TopicContent;
use Illuminate\Http\Request;
use Response;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class TopicContentController extends Controller
{
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
        $topics = Topic::all();
        $topics = Topic::paginate(20);
        $lessons = Lesson::all();
        $lessons = Lesson::paginate(20);

        $topics_content = TopicContent::all();
        $topics_content = TopicContent::paginate(20);

        $array_lesson_content_id = array();

        foreach($topics_content as $topic_content){
            $array_lesson_content_id []= $topic_content->lesson_id;
        }
 
        return view('topicsContent/index', ['topics' => $topics, 'topics_content' => $topics_content,  'array_topic_content_id' => $array_lesson_content_id, 'lessons'=>$lessons]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = $request->all();
       
        
        $lesson_id = $data['lesson_id'];
        $topic_id = DB::table('lessons')->where('id',$lesson_id)->value('topic_id');

        $i = 1;
        foreach($request->except(['lesson_id,topics_id']) as $data){

            if($request["content$i"] != null && $request["heading$i"] != null){
            try{
                $topic = TopicContent::create([
                    'content' => $request["content$i"],
                    'heading' => $request["heading$i"],
                    'lesson_id' => $lesson_id,
                    'topics_id' => $topic_id,
                ]);
                
                $msg = "The content was successfully added.";
                $type_msg = "success";
            }
            catch(\Exception $e){
                
                $msg = "Oops. There was an error and the content couldn't be added to the topic";
                $type_msg = "danger";
                
            }

        }
        $i++;
        };

        
        $topics = Topic::all();
        $lessons = Lesson::all();
        

        $topics_content = TopicContent::all();

        $array_lesson_content_id = array();

        foreach($topics_content as $topic_content){
            $array_lesson_content_id []= $topic_content->lesson_id;
        }

        $topics = Topic::all();
        $topics = Topic::paginate(20);
        $lessons = Lesson::all();
        $lessons = Lesson::paginate(20);

        $topics_content = TopicContent::all();
        $topics_content = TopicContent::paginate(20);
 

        //return Redirect::route('topic.content',['topics' => $topics, 'topics_content' => $topics_content,  'array_topic_content_id' => $array_lesson_content_id, 'lessons'=>$lessons, 'msg' => $msg, 'type_msg' => $type_msg]);
        return view('topicsContent/index',['topics' => $topics, 'topics_content' => $topics_content,  'array_topic_content_id' => $array_lesson_content_id, 'lessons'=>$lessons, 'msg' => $msg, 'type_msg' => $type_msg]);
        
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'content' => ['required'],
            'lesson_id' => ['required','unique:topic_contents,lesson_id'],
        ]);
    }

    public function getJson($id)
    {
        $topic_content = TopicContent::find($id);
        
        return Response::json(array('success' => true, 'data' => $topic_content), 200);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TopicContent  $topicContent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $topic_content = TopicContent::find($id);
        if (!$topic_content) {
            return Response::json(404);
        }

        $validator = Validator::make($request->all(), [
            'content' => ['required'],
            'heading' => ['required'],
        ]);
        // Validate the input and return correct response
        if ($validator->fails()) {
            return Response::json(array(
                'success' => false,
                'data' => $request->all(),
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        if ($request->exists('content')) {
            $topic_content->content = $request->get('content');
        }
        if ($request->exists('heading')) {
            $topic_content->heading = $request->get('heading');
        }


        $topic_content->save();
        return Response::json(array('success' => true, 'data' => $request->all()), 200);
    }


    public function delete(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'topic_content_id' => ['required', 'int', 'exists:topic_contents,id'],
        ]);
        // Validate the input and return correct response
        if ($validator->fails()) {
            return Response::json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        }
        TopicContent::find($request->get('topic_content_id'))->delete();

        return Response::json(array('success' => true), 200);
    }

    
}
