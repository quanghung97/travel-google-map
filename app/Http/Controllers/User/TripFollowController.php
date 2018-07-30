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
    public function index()
    {
        //
    }

    public function flow($trip_id){
        $user = Auth::user();
        $user->trips()->attach($trip_id,['status'=>'follow']);
        return Redirect::back();
    }

    public function unflow($trip_id){
        $user = Auth::user();
        $user->trips()->detach($trip_id);
    }
}
