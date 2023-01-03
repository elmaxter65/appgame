<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showResetForm($token,$email)
{   
    
    $email_decrypt = $email;
    return view('auth.passwords.reset')->with(['token' => $token, 'email' => $email_decrypt]);
}

public function updatePass(Request $request){
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
        'password_confirmation' => 'required|same:password',
        'token' => 'required'

    ]);
    if ($validator->fails()) {
        return Redirect::back()->withErrors($validator);
    }

    // order to get the last token
    $users_pass_rest = DB::table('password_resets')->get();
   
    foreach($users_pass_rest as $user_pass_reset){
        
        if(decrypt($user_pass_reset->email) == strtolower($request->get('email'))){
            if(Hash::check($request->get('token'), $user_pass_reset->token)){
                // update password
                $user_id = User::where('email',$user_pass_reset->email)->get('id')->first();
                $user = User::find($user_id); 
                $user[0]->password = Hash::make($request->get('password'));
                $user[0]->updated_at = Carbon::now();
                $user[0]->password_changed_at = Carbon::now();
                $user[0]->save();
                return view('password-changed');
            }else{
                return Redirect::back()->withErrors(['error' => 'Expired reset link!']);
            }
            break;
        }
    }
    
    return Redirect::back()->withErrors(['error' => 'Invalid user']);
}
}
