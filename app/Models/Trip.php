<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'trips';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'image_url', 'people_number', 'status', 'owner_id'];

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_trip', 'trip_id', 'user_id')->withPivot('status');
    }

    public function owner()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function usersFollow()
    {
        return $this->belongsToMany('App\Models\User', 'user_trip', 'trip_id', 'user_id')
        ->wherePivot('status', 'follow');
    }

    public function usersVerify()
    {
        return $this->belongsToMany('App\Models\User', 'user_trip', 'trip_id', 'user_id')
        ->wherePivot('status', 'waiting verify');
    }

    public function usersJoin()
    {
        return $this->belongsToMany('App\Models\User', 'user_trip', 'trip_id', 'user_id')
        ->wherePivot('status', 'join');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'trip_id', 'id');
    }

    public function wayPoints()
    {
        return $this->hasMany('App\Models\WayPoint', 'trip_id', 'id');
    }
}
