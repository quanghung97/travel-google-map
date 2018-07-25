<?php

namespace App\Http\Controllers\User\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Facades\UserRepository;
use App\Repositories\Facades\TripRepository;

class ListController extends Controller
{
    public function newest()
    {
        $trip = TripRepository::orderBy('created_at', 'desc')->get();
        //dd($trip);
        return view('user.home.new.index', compact('trip'));
    }

    public function hotest()
    {
        $listFollowJoinUser = TripRepository::getAllTripHotest();
        dd($listFollowJoinUser);
        //dd($listFollowJoinUser[0]->usersJoin_count);
        return view('user.home.hot.index', compact('trip'));
    }

    public function newestmem()
    {
        $user = UserRepository::orderBy('created_at', 'desc')->get();
        dd($user);

        return view('user.home.member.index', compact('trip'));
    }
}
