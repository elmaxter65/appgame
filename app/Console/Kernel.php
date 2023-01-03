<?php

namespace App\Console;

use App\UserApp;
use App\GamePoint;
use Carbon\Carbon;
use App\LoginAttemp;
use App\UserResponse;
use App\UserSituation;
use App\UserAchievement;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $now = Carbon::now();
            $users = UserApp::all();
            foreach($users as $user){
                if($user->created_at->diffInYears($now) == 10){

                    // delete all his records
                    UserResponse::where('user_id',$user->id)->delete();
                    LoginAttemp::where('user_id',$user->id)->delete();
                    UserSituation::where('user_id',$user->id)->delete();
                    UserAchievement::where('user_id',$user->id)->delete();
                    GamePoint::where('user_id',$user->id)->delete();
                    DB::table('oauth_access_tokens')->where('user_id',$user->id)->delete();
                    $user->delete();
                }
            }
        })->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
