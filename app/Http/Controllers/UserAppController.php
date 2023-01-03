<?php

namespace App\Http\Controllers;

use Response;
use Validator;
use App\UserApp;
use App\GamePoint;
use App\LoginAttemp;
use App\UserResponse;
use App\UserSituation;
use App\UserAchievement;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserAppController extends Controller
{

    use UploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        //$this->middleware('auth')->except(['userConfirm','confirmPass']);
    }
    
    public function index()
    {
        
        $usersApp = UserApp::sortable()->paginate(20);
        
 
        return view('usersApp/index',['usersApp' => $usersApp]);
    }

    public function getJson($id)
    {
        $userApp = UserApp::find($id);
        
            return Response::json(array('success' => true, 'data' => $userApp), 200);  
    }


    public function search(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ]);

        $users_app = UserApp::all();
        $user_result = null;
        if(!$users_app->isEmpty()){
            foreach($users_app as $user_app){
                if(decrypt($user_app->email) == strtolower($request->get('email'))){
                    $user_result =$user_app;
                    break;
                }
            }
            
        }
        $usersApp = UserApp::sortable()->paginate(20);

        if($user_result != null){
            return view('usersApp/index',['usersApp' => $usersApp, 'userSearch' => $user_result]);
        }else{

            return view('usersApp/index',['usersApp' => $usersApp, 'messageKo' => 'No results']);

        }



    }

    

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:155'],
            'email' => ['required', 'email', 'unique:user_apps,email'],
        ]);
    }


    public function delete(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'int', 'exists:user_apps,id'],
        ]);
        // Validate the input and return correct response
        if ($validator->fails()) {
            return Response::json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        }
        $user_id = $request->get('user_id');
        // delete all related to this user
        UserResponse::where('user_id',$user_id)->delete();
        LoginAttemp::where('user_id',$user_id)->delete();
        UserSituation::where('user_id',$user_id)->delete();
        UserAchievement::where('user_id',$user_id)->delete();
        GamePoint::where('user_id',$user_id)->delete();
        DB::table('oauth_access_tokens')->where('user_id',$user_id)->delete();
        UserApp::find($user_id)->delete();

        return Response::json(array('success' => true), 200);
    }

    public function confirm($id){

        $userApp = UserApp::find($id);
        if(!$userApp){
            return Response::json(404);
        }

        $userApp->state = true;
        $userApp->save();

        return view('emails.confirmed');
    }


    
}
