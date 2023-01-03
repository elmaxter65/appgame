<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ExpiredPasswordController extends Controller
{
    public function postExpired(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'current_password' =>'required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
            'password' => 'required|different:current_password|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
            'password_confirmation' => 'required|same:password'
    
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        $users_app = User::all();

        if($users_app->isEmpty()){
            return redirect()->back()->withErrors(['user' => 'Invalid credentials']);
        }

        foreach($users_app as $user_app){
            
            if(decrypt($user_app->email) == $request->get('email')){
                if(Hash::check($request->get('current_password'), $user_app->password)){
                    // update password
                    $user_app->password = Hash::make($request->get('password'));
                    $user_app->updated_at = Carbon::now();
                    $user_app->password_changed_at = Carbon::now();
                    $user_app->save();
                    return redirect()->back()->withErrors(['user' => 'Password has been changed']);
                }else{
                    return redirect()->back()->withErrors(['user' => 'Invalid credentials']);
                }
                break;
            }
        }
        
        
        return redirect()->back()->withErrors(['user' => 'Invalid credentials']);


    }
}
