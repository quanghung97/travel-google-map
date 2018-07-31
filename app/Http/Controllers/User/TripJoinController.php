<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Redirect;

class TripJoinController extends Controller
{
    public function index($id)
    {
        $data = User::find($id)->tripsJoin;
        return view('user.trip.join.index', compact('data'));
    }

    public function join($trip_id)
    {
        $trip = TripRepository::findOrFail($trip_id);
        $this->authorize('cantUpdateTrip', $trip);
        $user = Auth::user();
        $user->trips()->attach($trip_id, ['status'=>'join']);
        return Redirect::back()->with('message', 'Thư xin tham gia của bạn đã được gửi đến leader. Vui lòng chờ leader duyệt đơn của bạn');
    }

    public function unjoin($trip_id)
    {
        $trip = TripRepository::findOrFail($trip_id);
        $this->authorize('cantUpdateTrip', $trip);
        $user = Auth::user();
        $user->tripsJoin()->detach($trip_id);
        return Redirect::back()->with('message', 'Hủy tham gia chuyến đi thành công');
    }
}
