<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\User;
use App\Models\Trip;
use Redirect;


class TripFollowController extends Controller
{
    public function index($id)
    {
        $data = User::find($id)->tripsFollow;
        return view('user.trip.follow.index',compact('data'));
    }

    public function flow($trip_id){
        $user = Auth::user();
        $user->trips()->attach($trip_id,['status'=>'follow']);
        return Redirect::back()->with('message','Theo dõi chuyến đi thành công');
    }

    public function unflow($trip_id){
        $user = Auth::user();
        $user->trips()->detach($trip_id);
        return Redirect::back()->with('message','Bỏ theo dõi chuyến đi thành công');;
    }
}
