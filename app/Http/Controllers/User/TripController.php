<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\StoreTripRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Facades\TripRepository;
use App\Repositories\Facades\WayPointRepository;
use Illuminate\Http\Request;
use Auth;
use Redirect;
use App\Models\WayPoint;
use Carbon\Carbon;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userid = Auth::user()->id;
        $trip = TripRepository::with('wayPoints')->where('owner_id', $userid)->get();

        return view('user.trip.index', compact('trip'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.trip.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTripRequest $request)
    {
        $current = Carbon::now();
        $userid = Auth::user()->id;
        $requestData = $request->except('name', 'file', '_token', 'leave_time0', 'arrival_time0');
        if ($current > $request->leave_time0) {
            return Redirect::back()->withErrors(['errors' => 'Thời gian rời điểm xuất phát phải là 1 thời gian tương lai']);
        }

        if ($request->leave_time0 >  $request->arrival_time0) {
            return Redirect::back()->withErrors(['errors' => 'Thời gian kết thúc phải sau thời gian ban đầu']);
        }
        $trip = TripRepository::create([
            'name' => $request->name,
            'owner_id' => $userid,
        ]);
        if ($request->hasFile('file')) {
            $img_file = $request->file('file');
            $check = TripRepository::updateImage($trip->id, $img_file);
            if ($check == false) {
                return Redirect::back()->withErrors(
                    [ 'errors' => 'Định dạng hình ảnh không hợp lệ (chỉ hỗ trợ các định dạng: png, jpg, jpeg)!' ]
                );
            }
        }
        WayPointRepository::createMultiWayPoint($requestData, $trip->id);
        WayPointRepository::createLeaveArrivalTime($request->leave_time0, $request->arrival_time0, $trip->id);

        return Redirect('user/trip/'.$trip->id.'/edit');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //eager loading wayPoints
        $trip = TripRepository::with('wayPoints')->findOrFail($id);
        $verify = $trip->usersVerify()->get();
        $join = $trip->usersJoin()->get();
        return view('user.trip.show', compact('trip', 'join', 'verify'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $trip = TripRepository::with('wayPoints')->findOrFail($id);
        $this->authorize('updateTrip', $trip);
        $this->authorize('ablePlan', $trip);
        return view('user.trip.edit', compact('trip'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //eager loading
        $trip = TripRepository::with('wayPoints')->findOrFail($id);
        $this->authorize('updateTrip', $trip);
        $this->authorize('ablePlan', $trip);
        $waypoint = $trip->wayPoints;
        $le_ti = 'leave_time'.(count($waypoint)-1);
        //dd(count($waypoint));
        $current = Carbon::now();

        if ($current > $request->leave_time0) {
            return Redirect::back()->withErrors(['errors' => 'Thời gian rời điểm xuất phát phải là 1 thời gian tương lai']);
        }

        if ($request->leave_time0 >= $request->arrival_time0) {
            return Redirect::back()->withErrors(['errors'=>'Thời gian kết thúc chuyến đi không thể nhỏ hơn thời gian chuyến đi bắt đầu']);
        }

        for ($i = 1; $i < count($waypoint); $i++) {
            $leave_time_1 = 'leave_time'.($i-1);
            $arrival_time_1 = 'arrival_time'.($i-1);
            $leave_time_2 = 'leave_time'.($i);
            $arrival_time_2 = 'arrival_time'.($i);

            if ($request->$leave_time_1 >= $request->$arrival_time_2) {
                return Redirect::back()->withErrors(['errors'=>'Thời gian đến điểm '.($i+1).' tiếp theo nhỏ hơn thời gian rời điểm trước đó']);
            }
            if ($request->$leave_time_2 <= $request->$arrival_time_2) {
                return Redirect::back()->withErrors(['errors'=>'Thời gian rời điểm '.($i+1).' không thể nhỏ hơn thời gian đến điểm đó']);
            }
        }

        if ($request->$le_ti >= $request->arrival_time0) {
            return Redirect::back()->withErrors(['errors'=>'Thời gian rời điểm không thể lớn hơn thời gian chuyến đi kết thúc']);
        }
        TripRepository::storeMultiTime($waypoint, $request);
        return Redirect::route('trip.index')->with('message', 'Cập nhật thành công!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $trip = TripRepository::findOrFail($id);
        $this->authorize('updateTrip', $trip);
        $this->authorize('ablePlan', $trip);
        $trip->wayPoints()->delete();
        $trip->users()->detach();
        $comments = $trip->comments();
        //dd($trip->comments);
        foreach ($trip->comments as $comment) {
            $comment->images()->delete();
        }

        $comments->delete();
        $trip->delete();
        return Redirect::back()->with('message', 'Xóa thành công');
    }

    public function edit_wayPoint($id)
    {
        $trip = TripRepository::with('wayPoints')->findOrFail($id);
        $this->authorize('updateTrip', $trip);
        $this->authorize('ablePlan', $trip);
        // dd($data);
        return view('user.trip.edit_waypoint', compact('trip'));
    }
    public function update_wayPoint(Request $request, $id)
    {
        $trip = TripRepository::with('wayPoints')->findOrFail($id);
        $this->authorize('ablePlan', $trip);
        $waypoint = $trip->wayPoints;
        $leave_time = $waypoint[0]->leave_time;
        $arrival_time = $waypoint[0]->arrival_time;
        for ($i = 0; $i < count($waypoint); $i++) {
            $waypoint[$i]->delete();
        }
        $requestData = $request->except('name', 'file', '_token', 'leave_time0', 'arrival_time0');
        WayPointRepository::createMultiWayPoint($requestData, $trip->id);
        WayPointRepository::createLeaveArrivalTime($leave_time, $arrival_time, $trip->id);
        return Redirect('user/trip/'.$trip->id.'/edit');
    }
}
