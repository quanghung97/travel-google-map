<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Facades\UserRepository;
use App\Repositories\Facades\TripRepository;
use Auth;
use Redirect;

class TripJoinController extends Controller
{
    public function index($id)
    {
        $data = UserRepository::findOrFail($id)->tripsJoin;
        return view('user.trip.join.index', compact('data'));
    }

    public function unjoin($trip_id)
    {
        $trip = TripRepository::findOrFail($trip_id);
        $this->authorize('cantUpdateTrip', $trip);
        $this->authorize('ablePlan', $trip);
        $user = Auth::user();
        $user->tripsJoin()->detach($trip_id);
        return Redirect::back()->with('message', 'Hủy tham gia chuyến đi thành công');
    }

    public function out($user_id, $trip_id)
    {
        $user = UserRepository::findOrFail($user_id);
        $trip = $user->trips()->wherePivot('trip_id', $trip_id)->first();
        $this->authorize('updateTrip', $trip);
        $this->authorize('ablePlan', $trip);
        $user->tripsJoin()->detach($trip_id);
        return Redirect::back()->with('message', 'Kích thành viên thành công');
    }
}
