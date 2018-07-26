<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\StoreTripRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Facades\TripRepository;
use App\Repositories\Facades\WayPointRepository;
use Auth;
use Redirect;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trip = TripRepository::all();

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
        $userid = Auth::user()->id;
        $requestData = $request->except('name', 'file', '_token');
        if ($request->hasFile('file')) {
            $img_file = $request->file('file');
            $img_file_extension = $img_file->getClientOriginalExtension();
            if ($img_file_extension != 'PNG' && $img_file_extension != 'jpg' && $img_file_extension != 'jpeg' && $img_file_extension != 'png') {
                return Redirect::back()->withErrors(
                    [ 'errors' => 'Định dạng hình ảnh không hợp lệ (chỉ hỗ trợ các định dạng: png, jpg, jpeg)!' ]
                );
            }
            $trip = TripRepository::create([
                'name' => $request->name,
                'owner_id' => $userid,
            ]);

            WayPointRepository::createMultiWayPoint($requestData, $trip->id);

            TripRepository::updateImage($trip->id, $img_file);
        } else {
            $trip = TripRepository::create([
                'name' => $request->name,
                'owner_id' => $userid,
            ]);

            WayPointRepository::createMultiWayPoint($requestData, $trip->id);
        }
        return Redirect::back()->with('message','Tạo thành công chuyến đi');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
