<?php

namespace App\Http\Controllers;

use App\Topic;
use App\UserApp;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        // total users
        $users= UserApp::all();
        $num_total_users= $users->count();

        // confirmed users
        $users_confirmed = UserApp::where('state',true);
        $num_users_confirmed = $users_confirmed->count();

        //new users (last 7 days)
        $end = Carbon::now();
        $start = Carbon::now()->subDays(7);
        $new_users = UserApp::where('created_at','>=',$start)->where('created_at','<=',$end);
        $num_new_users = $new_users->count();

        // topics
        $topics = Topic::all();
        
        return view('home',['num_total_users' => $num_total_users, 'num_users_confirmed' => $num_users_confirmed,'num_new_users' => $num_new_users, 'topics' => $topics]);
    }
}
