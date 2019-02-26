<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscribedUser extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable
        = [
            'twitch_id',
            'liked_by_id',
        ];

    public function user()
    {
        return $this->belongsTo('');
    }
}