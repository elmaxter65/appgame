<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Notifications\UserResetPasswordNotification;
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
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function sendResetLinkEmail(Request $request)
{
    $input = $request->all();
    $rules = array(
        'email' => "required",
    );
    $validator = Validator::make($input, $rules);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator->errors());
    }

    $users_cms = User::all();
        if($users_cms->isEmpty()){
            return redirect()->back()->withErrors(['Invalid email']);
        }

        foreach($users_cms as $user_cms){
            if(decrypt($user_cms->email) == strtolower($request['email'])){
                if($user_cms->isUserBlocked()){
                    return redirect()->back()->withErrors(['This user is blocked for too many failed attemps. Please wait 30 minutes.']);
                    
                }

                $user_cms->unblockUser();

                $token = Password::getRepository()->create($user_cms);

                try {
                    $user_cms->notify(new UserResetPasswordNotification($token,decrypt($user_cms->email)));
            
                    return redirect()->back()->withErrors(['We have emailed you a link to reset your password.']);
                } catch (Exception $e) {
            
                    return redirect()->back()->withErrors(['Error sending the email to reset the password.']);
                }
                

                

            }else{
                return redirect()->back()->withErrors(['Invalid email']);
            }
        }

     
    
}


}
