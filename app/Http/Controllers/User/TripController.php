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
        // dd($request);
        $userid = Auth::user()->id;
        $requestData = $request->except('name', 'file', '_token', 'leave_time0', 'arrival_time0');
        // dd($requestData);
        $trip = TripRepository::create([
            'name' => $request->name,
            'owner_id' => $userid,
        ]);
        if ($request->hasFile('file')) {
            $img_file = $request->file('file');
            $img_file_extension = $img_file->getClientOriginalExtension();
            if ($img_file_extension != 'PNG' && $img_file_extension != 'jpg' && $img_file_extension != 'jpeg' && $img_file_extension != 'png') {
                
                $trip->delete();
                return Redirect::back()->withErrors(
                    [ 'errors' => 'Định dạng hình ảnh không hợp lệ (chỉ hỗ trợ các định dạng: png, jpg, jpeg)!' ]
                );
            }
            TripRepository::updateImage($trip->id, $img_file);
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
        return view('user.trip.show', compact('trip'));
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
        if (Auth::user()->id == $trip->owner_id) {
            return view('user.trip.edit', compact('trip'));
        }
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
        $trip = TripRepository::findOrFail($id);
        $waypoint = WayPoint::where('trip_id',$id)->get();
        for( $i = 0; $i < count($waypoint); $i++){
            $action = 'action'.$i;
            $leave_time = 'leave_time'.$i;
            $arrival_time = 'arrival_time'.$i;
            $waypoint[$i]->action = $request->$action;
            $waypoint[$i]->leave_time = $request->$leave_time;
            $waypoint[$i]->arrival_time = $request->$arrival_time;
            $waypoint[$i]->save();
        }
        return Redirect::route('trip.index')->with('message','Cập nhật thành công!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $trip = TripReponsitory::findOrFail($id);
    }
}
