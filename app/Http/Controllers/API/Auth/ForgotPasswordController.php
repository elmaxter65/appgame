<?php

namespace App\Http\Controllers\API\Auth;

use App\UserApp;
use App\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Notifications\UserAppResetPasswordNotification;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    

    protected function broker()
{
    return Password::broker('user_apps');
}

// send the email with the reset link
public function sendResetLinkEmail(Request $request){
    $input = $request->all();
    $rules = array(
        'email' => "required",
    );
    $validator = Validator::make($input, $rules);
    if ($validator->fails()) {
        $arr = array("status" => 400, "message" => $validator->errors()->first());
    } else {

        // check if user already exists
    $users = UserApp::all();
    if(!$users->isEmpty()){
        foreach($users as $user){
            if(decrypt($user->email) == strtolower($request['email'])){
            break;
            }
            $user = false;
        }
        
    }else{
        $user = false;
    }

    if(!$user){
        $arr = array("status" => 400, "message" => "That user doesn't exit");
        return \Response::json($arr);
    }

    // create reset pass token
    $token = Password::getRepository()->create($user);
    try {
        $user->notify(new UserAppResetPasswordNotification($token,$user->email));

        $arr = array("status" => 200, "message" => "Email to reset password sent");
    } catch (Exception $e) {

        $arr = array("status" => 500, "message" => "Error sending email to reset password");
    }

    return \Response::json($arr);
    
        
}


}


}
