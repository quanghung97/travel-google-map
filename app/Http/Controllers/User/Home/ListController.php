<?php

namespace App\Http\Controllers\User\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Facades\UserRepository;
use App\Repositories\Facades\TripRepository;
use Auth;

class ListController extends Controller
{
    public function newest()
    {
        $trip = TripRepository::with('owner')->orderBy('created_at', 'desc')->limit(10)->get();
        return view('user.home.new.index', compact('trip'));
    }

    public function hotest()
    {
        $trip = TripRepository::getTripHotest();
        return view('user.home.hot.index', compact('trip'));
    }

    public function newestmem()
    {
        $user = UserRepository::orderBy('created_at', 'desc')
        ->where('id', '<>', 1)->where('id', '<>', Auth::user()->id)->limit(10)->get();
        return view('user.home.member.index', compact('user'));
    }
}
