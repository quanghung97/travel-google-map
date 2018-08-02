<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'comments';

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
    protected $fillable = ['content', 'trip_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function trip()
    {
        return $this->belongsTo('App\Models\Trip', 'id', 'trip_id');
    }

    public function replies()
    {
        return $this->hasMany('App\Models\Comment', 'parent_id', 'id');
    }

    public function parentReply()
    {
        return $this->belongsTo('App\Models\Comment', 'id', 'parent_id');
    }

    public function images()
    {
        return $this->hasMany('App\Models\Image', 'comment_id', 'id');
    }

    /**
    * Get all of the owning commentable models.
    */
    public function commentable()
    {
        return $this->morphTo();
    }

    public function comments()
    {
        return $this->morphMany('App\Models\Comment', 'commentable');
    }
}
