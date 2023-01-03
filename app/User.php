<?php

namespace App;

use Carbon\Carbon;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','password_changed_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function routeNotificationForMail($notification)
  {
    return decrypt($this->email);
  }

    public function blockUser(){
        
        $this->blocked = true;
        $this->blocked_time = Carbon::now();
        $this->save();
    }

    public function isUserBlocked(){
       
        if($this->blocked == true && (Carbon::now()->diffInMinutes($this->blocked_time) < config('custom.blocked_waiting_time'))){
            return true;
        }else{
            return false;
        }
    }

    public function unblockUser(){
        
        $this->blocked = false;
        $this->blocked_time = null;
        $this->save();
    }
}
