<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Redirect;

class TripVerifyController extends Controller
{
    public function verify($trip_id){
        $user = Auth::user();
        $user->trips()->attach($trip_id,['status'=>'waiting verify']);
        return Redirect::back()->with('message','Thư xin tham gia của bạn đã được gửi đến leader. Vui lòng chờ leader duyệt đơn của bạn');
    }

    public function unverify($trip_id){
        $user = Auth::user();
        $user->trips()->wherePivot('status','waiting verify')->detach($trip_id);
        return Redirect::back()->with('message','Đã hủy đơn xin tham gia chuyến đi của bạn');
    }

    public function deny($user_id,$trip_id){
        $user = User::find($user_id);
        $user->trips()->wherePivot('status','waiting verify')->detach($trip_id);
        return Redirect::back();
    }

    public function accept($user_id,$trip_id){
        $user = User::find($user_id);
        $user->trips()->updateExistingPivot($trip_id,['status'=>'join']);
        return Redirect::back();
    }
}
