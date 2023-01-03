<?php

namespace App\Http\Controllers;


use App\User;
use Response;
use Carbon\Carbon;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    use UploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth')->except('logout');
    }
    
    public function changePass(Request $request){
        
        $user = Auth::user();
 

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|different:current_password|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
            'new_password_confirmation' => 'required|same:new_password',
        ]);
        // Validate the input and return correct response
        if ($validator->fails()) {
           
            
            return redirect()->back()->withErrors($validator->getMessageBag()->toArray());
            
        }

        $users_cms = User::all();
        if($users_cms->isEmpty()){
            return redirect()->back()->withErrors(['user', 'Invalid credentials']);
        }

       
        
        if(Hash::check($request['current_password'], $user->password)){

                    $user->password = Hash::make($request['new_password']);
                    $user->password_changed_at = Carbon::now();
                    $user->save();
                    $msg = "Password changed";
                    $type_msg = "success";
                    return view('changepass/index',['msg' => $msg, 'type_msg' => $type_msg]);


                }else{
                    $msg = "Incorrect current password";
                    $type_msg = "danger";
                    return response()->json(["success" => false]);

                    return view('changepass/index',['msg' => $msg, 'type_msg' => $type_msg]);
                }
            
        

        $msg = "Error when changing the password";
        $type_msg = "danger";
        return view('changepass/index',['msg' => $msg, 'type_msg' => $type_msg]);

    }

    public function logout(){
        Auth::logout();
	    return Redirect::route('home');
    }

    
}
