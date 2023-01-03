<?php

namespace App\Http\Controllers\API\Auth;

use App\UserApp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;


class ResetPasswordController extends Controller
{


public function showResetForm($token,$email)
{
    $email = decrypt($email);
    return view('auth.passwords.userapp-reset')->with(['token' => $token, 'email' => $email]);
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
                $user_id = UserApp::where('email',$user_pass_reset->email)->get('id')->first();
                $user = UserApp::find($user_id);
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
