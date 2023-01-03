<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Carbon\Carbon;
use App\LoginAttemp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
            $this->middleware('guest')->except('logout'); 
    }

    public function login(Request $request)
    {
        
        $users_cms = User::all();
        if($users_cms->isEmpty()){
            return redirect()->back()->withErrors(['Invalid credentials']);
        }

        foreach($users_cms as $user_cms){
            if(decrypt($user_cms->email) == strtolower($request['email'])){
                if(Hash::check($request['password'], $user_cms->password)){

                    if($user_cms->isUserBlocked()){
                        return redirect()->back()->withErrors(['Too many failed attemps. Please wait 30 minutes.']);
                        
                    }

                    $user_cms->unblockUser();

                    if($this->passwordExpiricy($user_cms)){
                        return view('auth.passwords.expired');
                    }

                    // reset number of attemps to 0
                    $this->resetLoginAttemps($user_cms->id);

                    Auth::login($user_cms);
                    return $this->sendLoginResponse($request);
                }else{

                    // check number of attemps to block the user
                    if ($this->getLoginAttemps($user_cms->id) >= config('custom.max_login_attemps')){
                        //Fire the lockout event.
                        $user_cms->blockUser();
                        
                        //redirect the user back after lockout.
                        return redirect()->back()->withErrors(['Too many failed attemps. Please wait 30 minutes.']);
                        
                    }

                    $this->loginAttempts($user_cms->id);
                    
                    return redirect()->back()->withErrors(['Invalid credentials']);
                }
            }
        }
        
        return redirect()->back()->withErrors(['Invalid credentials']);
    }

    public function passwordExpiricy($user){
        $password_changed_at = new Carbon(($user->password_changed_at) ? $user->password_changed_at : $user->created_at);

        if (Carbon::now()->diffInDays($password_changed_at) >= config('custom.password_expires_days')) {
            return true;
        }else{
            return false;
        }
    }

    public function loginAttempts($user_cms_id){
        $login_attemp = LoginAttemp::where('user_cms_id',$user_cms_id)->get()->first();
        if($login_attemp == null){
            LoginAttemp::create([
                'user_cms_id' => $user_cms_id,
                'num_attemps' => 1,
            ]);
        }else{
            $login_attemp->num_attemps = $login_attemp->num_attemps + 1;
            $login_attemp->save();
        }
    }

    public function getLoginAttemps($user_cms_id){
        $login_attemp = LoginAttemp::where('user_cms_id',$user_cms_id)->get()->first();
        if($login_attemp == null){
            return 0;
        }else{
            return $login_attemp->num_attemps;
        }
    }

    public function resetLoginAttemps($user_cms_id){
        $login_attemp = LoginAttemp::where('user_cms_id',$user_cms_id)->get()->first();
        if($login_attemp != null){
            $login_attemp->num_attemps = 0;
            $login_attemp->save();
        }
    }

}
