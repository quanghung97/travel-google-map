<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Facades\TripRepository;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trip = TripRepository::with('wayPoints')->get();

        return view('user.show_all_trips', compact('trip'));
    }
}
