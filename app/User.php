<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'twitch_id',
        'nickname',
        'name',
        'email',
        'avatar',
        'token',
        'refresh_token',
        'expires_in',
    ];

    public function accounts() {
      return $this->hasMany('App\SocialAccount');
    }

//    public function likedUsers(){
//        return $this->hasMany('App\SocialAccount');
//    }
}
