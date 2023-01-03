<?php

namespace App\Http\Controllers\API\Auth;


use App\User;
use App\UserApp;
use Carbon\Carbon;
use App\Mail\NewUser;
use App\TokenNotification;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */


    public $successStatus = 200;


    public function register(Request $request)
    {
        $factory = (new Factory)->withServiceAccount(__DIR__.'/../../abbott-dev-firebase-adminsdk-vmges-cf7cdf32a6.json');
        $messaging = $factory->createMessaging();

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
            'c_password' => 'required|same:password',
            'check_policy' => 'required',
            'check_comercial' => "required",
            'token' => 'nullable'

        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        if ($request->get('check_policy') != 'true') {
            return response()->json(['error' => 'You need to accept the terms & conditions'], 401);
        }

        $input = $request->all();
        // check the user doesn't exist
        $users_app = UserApp::all();
        if(!$users_app->isEmpty()){
            foreach($users_app as $user_app){
                if(decrypt($user_app->email) == strtolower($input['email'])){
                    $user_exist = true;
                    break;
                }
                $user_exist = false;
            }



        }else{
            $user_exist = false;
        }


        if($user_exist == false){
            // check if there is an admin with that email
            $users_admin = User::all();
            if(!$users_admin->isEmpty()){
                foreach($users_admin as $user_admin){
                    if(decrypt($user_admin->email) == strtolower($input['email'])){
                        $user_exist = true;
                        break;
                    }
                    $user_exist = false;
                }
            }else{
                $user_exist = false;
            }

        }



        if ($user_exist == true) {
            return response()->json(['success' => 'this email is already taken'],  200);
        }

        $email_hash = Crypt::encrypt(strtolower($input['email']));

        $check_comercial = $request['check_comercial'];

        if($check_comercial == "true" || $check_comercial == true){
            $check_comercial = 1;
        }else{
            $check_comercial = 0;
        }
        $user = UserApp::create([
            'email' => $email_hash,
            'password' => Hash::make($input['password']),
            'points' => 0,
            'state' => 0,
            'status' => "Bronze",
            'check_policy' => Carbon::now(),
            'check_comercial' => $check_comercial,
            'password_changed_at' => Carbon::now(),

        ]);

        if($request->has('token')){
            $token = encrypt($request['token']);

        }else{
            $token = null;
        }

        $token_item = TokenNotification::create([
            'user_id' => $user->id,
            'token' => $token,
        ]);

        if($token != null){
            $token_decrypt = decrypt($token_item->token);
            $messaging->subscribeToTopic('all',$token_decrypt);
        }


        try {
            Mail::to(decrypt($user->email))->send(new NewUser($user));
        } catch (\Exception $e) {
            $success['error'] =  'Email to confirm not send';

            return response()->json(['success' => $success],  200);
        }
        return response()->json(['success' => true],  200);
    }


}
