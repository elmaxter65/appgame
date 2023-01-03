<?php

namespace App\Http\Controllers\API\Auth;

use App\Game;
use App\UserApp;
use App\LiveCase;
use App\CasePoint;
use App\GamePoint;
use Carbon\Carbon;
use App\Achievement;
use App\LoginAttemp;
use App\TokenNotification;
use Laravel\Passport\Token;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use App\Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use phpDocumentor\Reflection\Types\Null_;

class LoginController extends Controller
{


    use ThrottlesLogins;

    protected $maxAttempts = 10;
    protected $decayMinutes = 30;

    public $successStatus = 200;

    public function username(){
        return 'email';
    }

    public function login(Request $request)
{
    $factory = (new Factory)->withServiceAccount(__DIR__.'/../../abbott-dev-firebase-adminsdk-vmges-cf7cdf32a6.json');
    $messaging = $factory->createMessaging();

    $users_app = UserApp::all();

    if($users_app->isEmpty()){
        return response()->json(['error' => 'Unauthorized'], 401);
    }

        foreach($users_app as $user_app){
            if(decrypt($user_app->email) == strtolower($request['email'])){
                if(Hash::check($request['password'], $user_app->password)){

                    if($user_app->isUserBlocked()){
                        return response()->json(['error' => 'Too many failed attemps. Please wait 30 minutes.'], 401);
                    }

                    $user_app->unblockUser();

                    // password expiry
                    $user = $user_app;
                    if($this->passwordExpiricy($user)){
                        return response()->json(['error' => 'Your password has expired. Please change it'], 410);
                    }


                    if ($user->name == null) {
                        $user->name = "";
                    }

                    if ($user->family_name == null) {
                        $user->family_name = "";
                    }
                    if ($user->nickname == null) {
                        $user->nickname = "";
                    }
                    if ($user->country == null) {
                        $user->country = "";
                    }
                    if ($user->city == null) {
                        $user->city = "";
                    }

                    if ($user->hospital == null) {
                        $user->hospital = "";
                    }
                    if ($user->occupation == null) {
                        $user->occupation = "";
                    }

                    if ($user->state == 1) {

                        $tokens = $user->tokens;
                        foreach($tokens as $token) {
                            $token->delete();
                        }
                        $tokenResult = $user->createToken('app');
                        $success['token'] =  $tokenResult->accessToken;
                        $success['expires_at'] =  Carbon::parse($tokenResult->token->expires_at)->toDateTimeString();


                        $user_points = GamePoint::where('user_id', $user->id)->get();
                        $topics = array();
                        foreach ($user_points as $user_point) {
                            $topic = $user_point->topic_id;
                            $topic_name = DB::table('topics')->where('id', $topic)->value('name');
                            if ($topic_name != null) {
                                if (!array_key_exists($topic_name, $topics)) {
                                    $topics[$topic_name] = $user_point->points;
                                } else {
                                    $topics[$topic_name] = $topics[$topic_name] + $user_point->points;
                                }
                            }
                        }

                        $game_points = Game::all();
                        $topics_game = array();
                        foreach ($game_points as $game_point) {
                            $topic = $game_point->topics_id;
                            $topic_name = DB::table('topics')->where('id', $topic)->value('name');
                            if ($topic_name != null) {
                                if (!array_key_exists($topic_name, $topics_game)) {
                                    $topics_game[$topic_name] = $game_point->points;
                                } else {
                                    $topics_game[$topic_name] = $topics_game[$topic_name] + $game_point->points;
                                }
                            }
                        }

                        $topics = (object) $topics;
                        $topics_game = (object) $topics_game;

                        // get the number of live cases with state true
                        $total_cases = LiveCase::all()->count();

                        // get the number of cases the user has done
                        $user_cases = CasePoint::where('user_id', $user->id)->get()->count();

                        $user_answer = UserApp::where('id',$user->id)->select('id','name','nickname','points','state','status', 'hospital', 'occupation','family_name','country','city','avatar_id')->get()->first();

                        if($user_answer->name != null){
                            $name = decrypt($user_answer->name);
                            $user_answer->name = $name;
                        }else{
                            $name = '';
                        }

                        $user_answer->name = $name;

                        if($user_answer->nickname != null){
                            $nickname = decrypt($user_answer->nickname);
                            $user_answer->nickname = $nickname;
                        }else{
                            $nickname = '';
                        }

                        $user_answer->nickname = $nickname;

                        if($user_answer->hospital != null){
                            $hospital = decrypt($user_answer->hospital);
                        }else{
                            $hospital = '';
                        }

                        $user_answer->hospital = $hospital;

                        if($user_answer->occupation != null){
                            $occupation = decrypt($user_answer->occupation);
                        }else{
                            $occupation = '';
                        }

                        $user_answer->occupation = $occupation;

                        if($user_answer->family_name != null){
                            $family_name = decrypt($user_answer->family_name);
                        }else{
                            $family_name = '';
                        }

                        $user_answer->family_name = $family_name;

                        if($user_answer->country != null){
                            $country = decrypt($user_answer->country);
                        }else{
                            $country = '';
                        }

                        $user_answer->country = $country;

                        if($user_answer->city != null){
                            $city = decrypt($user_answer->city);
                        }else{
                            $city = '';
                        }

                        $user_answer->city = $city;

                        // get the ranking
                        $user_answer->ranking = $user_answer->getUserRanking();


                        // get number of achievements the user has
                        $user_answer->user_numb_achievements = $user_answer->getNumAchievement();

                        // get total number of achievements
                        $user_answer->total_numb_achievements = count(Achievement::all());

                        // reset number of attemps to 0
                        $this->resetLoginAttemps($user->id);


                        //check if token device exist (notifications)
                        if($request->has('token_device')){
                            $token = request('token_device');
                        }else{
                            $token = null;
                        }

                        $userToken = TokenNotification::where('user_id', $user->id)->get();
                        $userTokens = [];
                        if ($request->has('token_device')) {
                            if(! $userToken->isEmpty()){
                                foreach($userToken as $userTok){
                                    if($userTok->token != null){
                                        $i = decrypt($userTok->token);

                                    }else{
                                        $i = null;
                                    }

                                    $userTokens [] = $i;

                                }
                                if(! in_array($token,$userTokens)){
                                $tokencreated = TokenNotification::create([
                                    'user_id' => $user->id,
                                    'token' => encrypt($token),
                                    ]);

                                    $tokencreated->active = $userTok->active;
                                    $tokencreated->save();


                                if($tokencreated->token != null && $tokencreated->active == 1){
                                        $messaging->subscribeToTopic('all', decrypt($tokencreated->token));
                                }


                                }
                            }else{
                                $tokencreated = TokenNotification::create([
                                'user_id' => $user->id,
                                'token' => encrypt($token),
                                ]);

                                if($tokencreated->token != null){
                                    $messaging->subscribeToTopic('all', decrypt($tokencreated->token));
                                }


                            }
                        }

                        if($token != null){
                            $tokens_user = DB::table('tokens')->where('user_id',$user_answer->id)->get();
                            foreach($tokens_user as $token_user){
                                if($token_user->token != null){
                                    if(decrypt($token_user->token) == $token){
                                        $token_active = $token_user->active;
                                    break;
                                    }
                                }

                            }

                        }else{
                            $token_active = 1;
                        }


                        // medals info
                        $medals = Status::select('name','points')->get();

                        //TODO: Solucion 
                        $test = new Collection();

                        foreach($medals as $medal){
                            $test->push(['name' => $medal->name, 'points'=>strval($medal->points)]);
                        }


                        return response()->json(['success' => $success, 'user' => $user_answer, 'points_per_topic' =>  $topics, 'total_points_games' =>  $topics_game, "cases_done" => $user_cases, "total_cases" => $total_cases, 'token_active'=> $token_active, 'medals' => $test],  200);
                    } else {
                        return response()->json(['error' => 'User not confirmed'], 401);
                    }

                }else{

                    // check number of attemps to block the user
                    if ($this->getLoginAttemps($user_app->id) >= config('custom.max_login_attemps')){
                        //Fire the lockout event.
                        $user_app->blockUser();
                        $this->fireLockoutEvent($request);

                        //redirect the user back after lockout.
                        return response()->json(['error' => 'Too many failed attemps. Please wait 30 minutes.'], 401);
                    }

                    $this->loginAttempts($user_app->id);
                    return $this->loginFailed();
                }

            }

        }

        return $this->loginFailed();

}


public function logout(){

        $user =Auth::user();
        $userid =$user->id;
        DB::table('oauth_access_tokens')->where('user_id', $userid)->update(['revoked' => true]);
        return response()->json(['success' => true], 200);

}


    private function loginFailed(){
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function passwordExpiricy($user){
        $password_changed_at = new Carbon(($user->password_changed_at) ? $user->password_changed_at : $user->created_at);

        if (Carbon::now()->diffInDays($password_changed_at) >= config('custom.password_expires_days')) {
            return true;
        }else{
            return false;
        }
    }

    public function loginAttempts($user_app_id){
        $login_attemp = LoginAttemp::where('user_id',$user_app_id)->get()->first();
        if($login_attemp == null){
            LoginAttemp::create([
                'user_id' => $user_app_id,
                'num_attemps' => 1,
            ]);
        }else{
            $login_attemp->num_attemps = $login_attemp->num_attemps + 1;
            $login_attemp->save();
        }
    }

    public function getLoginAttemps($user_app_id){
        $login_attemp = LoginAttemp::where('user_id',$user_app_id)->get()->first();
        if($login_attemp == null){
            return 0;
        }else{
            return $login_attemp->num_attemps;
        }
    }

    public function resetLoginAttemps($user_app_id){
        $login_attemp = LoginAttemp::where('user_id',$user_app_id)->get()->first();
        if($login_attemp != null){
            $login_attemp->num_attemps = 0;
            $login_attemp->save();
        }
    }


}
