<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Socialite;
use Auth;
use Redirect;
use Session;
use URL;
use App\Repositories\Facades\UserRepository;

class SocialAuthController extends Controller
{
    /**
     * Chuyển hướng người dùng sang OAuth Provider.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        if (!Session::has('pre_url')) {
            Session::put('pre_url', URL::previous());
        } else {
            if (URL::previous() != URL::to('login')) {
                Session::put('pre_url', URL::previous());
            }
        }
        return Socialite::driver('google')->redirect();
    }

    /**
     * Lấy thông tin từ Provider, kiểm tra nếu người dùng đã tồn tại trong CSDL
     * thì đăng nhập, ngược lại nếu chưa thì tạo người dùng mới trong SCDL.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        $userG = Socialite::driver('google')->user();
        //dd($userG);

        $authUser = UserRepository::findOrCreateUser($userG);
        Auth::login($authUser, true);
        return redirect('/home');
    }
}
