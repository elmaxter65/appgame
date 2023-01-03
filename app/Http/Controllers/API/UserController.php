<?php

namespace App\Http\Controllers\API;



use App\Token;
use App\Status;
use App\UserApp;
use Carbon\Carbon;
use App\Achievement;
use App\UserSituation;
use App\UserAchievement;
use Defuse\Crypto\Crypto;
use Kreait\Firebase\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    public $successStatus = 200;

    public function resetExpiredPassword(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'current_password' =>'required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
            'password' => 'required|different:current_password|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',


        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $users_app = UserApp::all();

        if($users_app->isEmpty()){
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        foreach($users_app as $user_app){

            if(decrypt($user_app->email) == $request->get('email')){
                if(Hash::check($request->get('current_password'), $user_app->password)){
                    // update password
                    $user_app->password = Hash::make($request->get('new_password'));
                    $user_app->updated_at = Carbon::now();
                    $user_app->password_changed_at = Carbon::now();
                    $user_app->save();
                    return response()->json(['message' => 'Password has been changed'], 200);
                }else{
                    return response()->json(['error' => 'Invalid credentials'], 401);
                }
                break;
            }
        }


        return response()->json(['error' => 'Invalid credentials'], 401);

    }

    public function updateUser(Request $request)
    {
        $user = Auth::user();



        if ($request->exists('password')) {
            if ($request->exists('password_to_change')) {
                if (Hash::check($request['password_to_change'], $user->password)) {
                    if ($request->exists('password')) {
                        $user->password = Hash::make($request['password']);
                        $user->password_changed_at = Carbon::now();
                    }
                } else {
                    return response()->json(["userUpdate" => 'incorrect actual password'],  200);
                }
            } else {
                return response()->json(["userUpdate" => 'actual password needed'],  200);
            }
        }

        if ($request->exists('state')) {
            $user->state = $request['state'];
        }
        if ($request->exists('name')) {
            $user->name = encrypt($request['name']);

        }
        if ($request->exists('nickname')) {
            $user->nickname = encrypt($request['nickname']);

        }
        if ($request->exists('family_name')) {
            $user->family_name = encrypt($request['family_name']);

        }
        if ($request->exists('country')) {
            $user->country = encrypt($request['country']);

        }

        if ($request->exists('city')) {
            $user->city = encrypt($request['city']);

        }

        if ($request->exists('hospital')) {
            $user->hospital = encrypt($request['hospital']);
        }
        if ($request->exists('occupation')) {
            $user->occupation = encrypt($request['occupation']);
        }

        if($request->exists('avatar_id')){
            if(is_numeric($request->get('avatar_id'))){
                $user->avatar_id = $request->get('avatar_id');
            }else{
                return response()->json(["userUpdate" => 'false'],  200);
            }

        }

        // notifications setting
        if ($request->exists('token_setting')) {

            if(is_numeric($request->get('token_setting'))){
                $token_setting = $request->get('token_setting');
                $this->updateNotSetting($token_setting);
            }else{
                return response()->json(["userUpdate" => 'false'],  200);
            }

        }

        if ($user->save()) {

            return response()->json(["userUpdate" => 'true'],  200);
        } else {
            return response()->json(["userUpdate" => 'false'],  200);
        }
    }


    public function getStatus()
    {
        $user = Auth::user();
        $status = Status::all();

        return response()->json(["status" => $status],  200);
    }


    /**
     * Busca en UserAchievement si el usuario tiene el logro en concreto.
     * Si no lo tiene se lo asigna.
     */
    private function grantAchievement(Achievement $achievement, $user)
    {
        $userHasAchievement = UserAchievement::where('user_id', $user->id)
            ->where('achievement_id', $achievement->id);

        if (!$userHasAchievement) {
            return UserAchievement::create([
                'user_id' => $user->id,
                'achievement_id' => $achievement->id,
                'points' => $achievement->points,
            ]);
        }
        return false;
    }


    private function getStatusName($points)
    {
        $statuses = DB::table('statuses')->get(['name', 'points']);
        $statuses = $statuses->reverse();

        foreach ($statuses as $status) {
            if ($points >= $status->points) {
                return $status->name;
            }
        }
        return $statuses[0]->name;
    }

    private function getUserIndex($users_list)
    {
        $i = 0;
        foreach($users_list as $user_list){

            if($user_list->id == Auth::user()->id){
                return $i;
            }
            $i = $i+1;
        }
    }

    private function getCloseToUser($users_list, $user_index)
    {
        $close_to_user = new Collection();
        $close_to_user->push($users_list[$user_index - 1]);
        $close_to_user->push($users_list[$user_index]);
        $close_to_user->push($users_list[$user_index + 1]);

        return $close_to_user;
    }

    public function getUsersRanking()
    {
        $user = Auth::user();
        $users_list = DB::table('user_apps')->orderBy('points', 'DESC')->get(['id', 'name', 'nickname', 'points', 'status']);

        foreach($users_list as $user){
            if($user->name != null){
                $name = decrypt($user->name);
                $user->name = $name;
            }else{
                $user->name = '';
            }

            if($user->nickname != null){
                $user_name = decrypt($user->nickname);
                $user->nickname = $user_name;
            }else{
                $user->nickname = '';
            }
        }


        // add ranking
        $rank = 1;
        foreach($users_list as $user){
            $user->ranking = $rank;
            $rank = $rank+1;
        }

        $count_users = count($users_list);

        if($count_users >= 9){
            $top_users = $users_list->take(3)->values();
            $bottom_users = $users_list->take(-3)->values();
            $close_to_user = new Collection();

            $is_user_on_top = $top_users->contains('id', Auth::user()->id);
            $is_user_on_bottom = $bottom_users->contains('id', Auth::user()->id);


            // user is in the middle
            if (!$is_user_on_top && !$is_user_on_bottom) {

                $user_index = $this->getUserIndex($users_list);

                $close_to_user = $this->getCloseToUser($users_list, $user_index);

            // los ultimos 3 o los 3 primeros
            }else {
                $close_to_user = [];
            }

        }else{
            return response()->json([
                "first_3" => [],
                "last_3" => [],
                "close_to_user" => [],
                "all_users" => $users_list,
                "total_users"=> $users_list
            ],  200);


        }



        return response()->json([
            "first_3" => $top_users,
            "last_3" => $bottom_users,
            "close_to_user" => $close_to_user,
            "total_users"=> $users_list
        ],  200);
    }




    public function getUserInfo()
    {
        $user = Auth::user();

        $user_answer = UserApp::where('id',$user->id)->select('id','name','points','state','status', 'hospital', 'occupation','family_name','nickname','country','city','avatar_id')->get()->first();
        if($user_answer->name != null){
            $name = decrypt($user_answer->name);
        }else{
            $name = '';
        }

        $user_answer->name = $name;

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

        if($user_answer->nickname != null){
            $nickname = decrypt($user_answer->nickname);
        }else{
            $nickname = '';
        }

        $user_answer->nickname = $nickname;

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

        // user token device status
        $user_answer->token_active = $user_answer->isTokenActive();

        // medals info
        $medals = Status::select('name','points')->get();
        $test = new Collection();

        foreach($medals as $medal){
            $test->push(['name' => $medal->name, 'points'=>strval($medal->points)]);
        }

        return response()->json(["user" => $user_answer, 'medals' => $test],  200);
    }

    public function getUserAchievements()
    {
        $user = Auth::user();
        $user_achievements = UserAchievement::where('user_id', $user->id)->get();
        foreach ($user_achievements as $user_achievement) {

            $achievement_title =  DB::table('achievements')->where('id', $user_achievement->achievement_id)->get()->first();
            $achievement_description =  DB::table('achievements')->where('id', $user_achievement->achievement_id)->get('description')->first();
            $user_achievement->achievement_title = $achievement_title->title;
            $user_achievement->achievement_description = $achievement_description->description;
        }

        return response()->json(["user_achievements" => $user_achievements],  200);
    }


    public function sendUserSituation(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $validator = Validator::make($request->all(), [
            'game_id' => 'required|exists:games,id',
            'level' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $exists = UserSituation::where('user_id', $user_id)->get()->first();

        if ($exists == null) {
            $userSituation = UserSituation::create([
                'game_id' => $request['game_id'],
                'user_id' => $user_id,
                'level' => $request['level'],
            ]);
        } else {
            $exists->game_id = $request['game_id'];
            $exists->level = $request['level'];

            $exists->save();
        }

        return response()->json(["updated" => 'true'],  200);
    }

    public function getUserSituation()
    {
        $user = Auth::user();
        $user_id = $user->id;

        $userSituation = UserSituation::where('user_id', $user_id)->get()->first();

        if ($userSituation == null) {
            $userSituation = "";
        }

        return response()->json(["userSituation" => $userSituation],  200);
    }

    public function setUserSituation($game_level, $user_id, $topic_id,$game,$lesson_id){

        if ($game_level == "challenge") {

            $exists = UserSituation::where('user_id', $user_id)->where(function ($query) use ($topic_id) {
                $query->where('topic_id', $topic_id)->where('level', 'challenge');
            })->get()->first();

            if ($exists == null) {
                $userSituation = UserSituation::create([
                    'game_id' => $game->id,
                    'user_id' => $user_id,
                    'level' => $game->level,
                    'topic_id' => $topic_id,
                    'lesson_id' => $lesson_id,
                    'round' => 0,
                ]);
            } else {
                $exists->game_id = $game->id;
                $exists->level = $game->level;

                $exists->save();
            }
        } else {

            $exists = UserSituation::where('user_id', $user_id)->where('lesson_id', $lesson_id)->get()->first();

            if ($exists == null) {
                $userSituation = UserSituation::create([
                    'game_id' => $game->id,
                    'user_id' => $user_id,
                    'level' => $game->level,
                    'topic_id' => $topic_id,
                    'lesson_id' => $lesson_id,
                    'round' => 0,
                ]);
            } else {
                $exists->game_id = $game->id;
                $exists->level = $game->level;

                $exists->save();
            }
        }

    }



    public function change_password(Request $request)
{
    $input = $request->all();
    $userid = Auth::guard('user_apps')->user()->id;
    $rules = array(
        'old_password' => 'required',
        'new_password' => 'required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
        'confirm_password' => 'required|same:new_password',
    );
    $validator = Validator::make($input, $rules);
    if ($validator->fails()) {
        $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
    } else {
        try {
            if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
                $arr = array("status" => 400, "message" => "Check your old password.", "data" => array());
            } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
                $arr = array("status" => 400, "message" => "Please enter a password which is not similar then current password.", "data" => array());
            } else {
                UserApp::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);
                $arr = array("status" => 200, "message" => "Password updated successfully.", "data" => array());
            }
        } catch (\Exception $ex) {
            if (isset($ex->errorInfo[2])) {
                $msg = $ex->errorInfo[2];
            } else {
                $msg = $ex->getMessage();
            }
            $arr = array("status" => 400, "message" => $msg, "data" => array());
        }
    }
    return \Response::json($arr);
}

public function updateNotSetting($token_setting){
    $user = Auth::user();

    $tokens = Token::where('user_id',$user->id)->get();
    foreach($tokens as $token){
            $token->active = $token_setting;
            $token->save();
                $factory = (new Factory)->withServiceAccount(__DIR__.'/../abbott-dev-firebase-adminsdk-vmges-cf7cdf32a6.json');
                $messaging = $factory->createMessaging();
            if($token_setting == false){
                $messaging->unsubscribeFromTopic('all', decrypt($token->token));

            }else if($token_setting == true){
                $messaging->subscribeToTopic('all', decrypt($token->token));
            }

    }

}


}
