<?php

namespace App\Http\Controllers;

use App\Topic;
use App\LiveCase;
use App\Equipment;
use App\EquipmentItem;
use App\Exploration;
use App\ExplorationItem;
use App\ExplorationAnswer;
use App\ExplorationAnswerItem;
use App\ExplorationItemChild;
use App\TreatmentAnswer;
use App\Treatment;
use App\Stent;
use App\StentAnswer;
use Illuminate\Http\Request;
use Response;
use Validator;
use App\Traits\UploadTrait;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class LiveCaseController extends Controller
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

        $livecases = LiveCase::all();
        $topics = Topic::all();

        return view('liveCases/index',['livecases' => $livecases, 'topics' => $topics]);
    }

    public function getJson($id)
    {
        $livecase = LiveCase::find($id);
        
        return Response::json(array('success' => true, 'data' => $livecase), 200);
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
            $livecase = LiveCase::create([
                'title' => $data['title'],
                'points' => $data['points'],
                'topic_id' => $data['topic_id'],
                'patient_name' => $data['patient_name'],
                'patient_age' => $data['patient_age'],
                'patient_sex' => $data['patient_sex'],
                'med_history' => $data['med_history'],
                'symptoms' => $data['symptoms'],
                'difficulty_level' => $data['difficulty_level'],
                'main_img' => "",
            ]);

                $livecase->main_img = $this->getImagePath($request->file('main_img'),$livecase->id);
            
            
            if($livecase->save()){
                    $equipment = Equipment::create([
                        'livecase_id' => $livecase->id,
                        'header' => $data['equipment_header'],
                    ]);
                
                $equipment_id = $equipment->id;

                for($i=1;$i<(count($request->all()));$i++){
                    if(($request->file("equipment_img_$i")) != null){

                        $equipmentItem = EquipmentItem::create([
                            'equipment_id' => $equipment_id,
                            'type' => "image",
                            'value' => $this->getImagePath($request->file("equipment_img_$i"),$livecase->id),
                            'percentage'=> $data["equipment_percentage_$i"],
                            'index' => ($i-1),
                        ]);
                    }
                }

                $exploration = Exploration::create([
                    'livecase_id' => $livecase->id,
                    'header' => $data['exploration_header'],
                ]);

                $explorationAnswer = ExplorationAnswer::create([
                    'exploration_id' => $exploration->id,
                    'header' => $data['exploration_answers_header'],
                ]);

                for($i=1;$i<(count($request->all()));$i++){
                    if(($request->exists("exploration_answer_$i"))){
                        $explorationAnswerItem = ExplorationAnswerItem::create([
                            'exploration_answers_id' => $explorationAnswer->id,
                            'value' => $data["exploration_answer_$i"],
                            'percentage' => $data["exploration_percentage_$i"],
                        ]);


                    }
                }


                for($i=1;$i<(count($request->all()));$i++){
                    if(($request->file("image1-$i")) != null){
                        $explorationItem = ExplorationItem::create([
                            'exploration_id' => $exploration->id,
                            'type' => "image",
                            'value' => $this->getImagePath($request->file("image1-$i"),$livecase->id),
                            'index' => ($i-1),
                        ]);

                        if(($request->file("image2-$i")) != null){
                            $explorationItemChild = ExplorationItemChild::create([
                                'exploration_item_id' => $explorationItem->id,
                                'type' => "image",
                                'value' => $this->getImagePath($request->file("image2-$i"),$livecase->id),
                            ]);

                        }
                    }
                }

                $treatment = Treatment::create([
                    'livecase_id' => $livecase->id,
                    'header' => $data['treatment_header'],
                ]);

                $treatment_id = $treatment->id;

                for($i=1;$i<(count($request->all()));$i++){
                    if(($request->exists("treatment_answer_$i"))){
                
                $treatmentAnswer = TreatmentAnswer::create([
                    'treatment_id' => $treatment_id,
                    'value' => $data["treatment_answer_$i"],
                    'percentage' => $data["treatment_percentage_$i"],
                ]);

                    }
                }

                $stent = Stent::create([
                    'livecase_id' => $livecase->id,
                    'header' => $data['stent_header'],
                ]);

                $stent_id = $stent->id;

                for($i=1;$i<(count($request->all()));$i++){
                    if(($request->exists("stent_answer_$i"))){
                
                $stentAnswer = StentAnswer::create([
                    'stent_id' => $stent_id,
                    'value' => $data["stent_answer_$i"],
                    'percentage' => $data["stent_percentage_$i"],
                ]);

                    }
                }


                $msg = "The live case was successfully created.";
                $type_msg = "success";



            }else{
                $msg = "Oops. There was an error and the live case couldn't be created";
                $type_msg = "danger";
                
            }
            
            
        }

        catch(\Exception $e){
            
            $msg = "Oops. There was an error and the live case couldn't be created";
            $type_msg = "danger";
            
        }
        
        $livecases = LiveCase::all();
        $topics = Topic::all();

        //return Redirect::route('live.case',['livecases' => $livecases, 'msg' => $msg, 'type_msg' => $type_msg]);
        return view('liveCases/index',['livecases' => $livecases, 'msg' => $msg, 'type_msg' => $type_msg, 'topics' => $topics]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'title' => ['required'],
            'patient_name' => ['required'],
            'patient_age' => ['required'],
            'patient_sex' => ['required'],
            'med_history' => ['required'],
            'difficulty_level' => ['required'],
            'symptoms' => ['required'],
            "main_img" => ['required','image','mimes:jpeg,png,jpg,gif','max:2048'],
            'equipment_header' => ['required'],
            'exploration_header' => ['required'],
            'exploration_answers_header' => ['required'],
        ]);
    }


    public function getImagePath($image,$livecase_id){

        $name = $livecase_id."_".time();
        $folder = 'games/';
        $filePath = $name. '.' . $image->getClientOriginalExtension();
        $this->uploadOne($image, $folder, 'public', $name);

        return $filePath;

    }

  
}
