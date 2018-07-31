<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'g_avatar_url',
        'g_id',
        'birthday',
        'gender'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = app('hash')->needsRehash($value)?Hash::make($value):$value;
        }
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'user_id', 'id');
    }

    public function trips()
    {
        return $this->belongsToMany('App\Models\Trip', 'user_trip', 'user_id', 'trip_id')->withPivot('status');
    }

    public function tripsOwner()
    {
        return $this->hasMany('App\Models\Trip', 'owner_id', 'id');
    }

    public function tripsFollow()
    {
        return $this->belongsToMany('App\Models\Trip', 'user_trip', 'user_id', 'trip_id')
        ->wherePivot('status', 'follow');
    }

    public function tripsVerify()
    {
        return $this->belongsToMany('App\Models\Trip', 'user_trip', 'user_id', 'trip_id')
        ->wherePivot('status', 'waiting verify');
    }

    public function tripsJoin()
    {
        return $this->belongsToMany('App\Models\Trip', 'user_trip', 'user_id', 'trip_id')
        ->wherePivot('status', 'join');
    }

    public function checkFollow($id_user, $id_trip){
        $user = User::find($id_user);
        $data = $user->tripsFollow()->get();
        foreach($data as $d){
            if($d->id ==  $id_trip) {
                return false;
            }
        }
        return true;
    }

    public function checkVerify($id_user, $id_trip){
        $user = User::find($id_user);
        $data = $user->tripsVerify()->get();
        foreach($data as $d){
            if($d->id ==  $id_trip) {
                return false;
            }
        }
        return true;
    }
    public function checkJoin($id_user, $id_trip){
        $user = User::find($id_user);
        $data = $user->tripsJoin()->get();
        foreach($data as $d){
            if($d->id ==  $id_trip) {
                return false;
            }
        }
        return true;
    }
}
