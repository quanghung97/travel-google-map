<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WayPoint extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'way_points';

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
    protected $fillable =[
        'lat', 'lng', 'address', 'action', 'leave_time', 'arrival_time', 'trip_id', 'order_num', 'vehicle'
    ];

    public function trip()
    {
        return $this->belongsTo('App\Models\Trip', 'id', 'trip_id');
    }
}
