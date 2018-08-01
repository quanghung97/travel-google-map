<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Facades\UserRepository;
use App\Repositories\Facades\TripRepository;
use Auth;
use Redirect;

class TripVerifyController extends Controller
{
    public function verify($trip_id)
    {
        $user = Auth::user();
        $trip = TripRepository::findOrFail($trip_id);
        $this->authorize('cantUpdateTrip', $trip);
        $this->authorize('ablePlan', $trip);
        $user->trips()->attach($trip_id, ['status'=>'waiting verify']);
        return Redirect::back()->with('message', 'Thư xin tham gia của bạn đã được gửi đến leader. Vui lòng chờ leader duyệt đơn của bạn');
    }

    public function unverify($trip_id)
    {
        $user = Auth::user();
        $trip = TripRepository::findOrFail($trip_id);
        $this->authorize('cantUpdateTrip', $trip);
        $this->authorize('ablePlan', $trip);
        $user->tripsVerify()->detach($trip_id);
        return Redirect::back()->with('message', 'Đã hủy đơn xin tham gia chuyến đi của bạn');
    }

    public function deny($user_id, $trip_id)
    {
        $user = UserRepository::findOrFail($user_id);
        $trip = $user->trips()->wherePivot('trip_id', $trip_id)->first();
        $this->authorize('updateTrip', $trip);
        $this->authorize('ablePlan', $trip);
        $user->tripsVerify()->detach($trip_id);
        return Redirect::back();
    }

    public function accept($user_id, $trip_id)
    {
        $user = UserRepository::findOrFail($user_id);
        $trip = $user->trips()->wherePivot('trip_id', $trip_id)->first();
        $this->authorize('updateTrip', $trip);
        $this->authorize('ablePlan', $trip);
        $user->tripsVerify()->updateExistingPivot($trip_id, ['status'=>'join']);
        return Redirect::back();
    }
}
