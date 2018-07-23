<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Override a form Login and Register.
     *
     * @return void
     */

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ],[
            'email.required'=>'Bạn chưa nhập email',
            'email.email'=>'Định dạng email không đúng',
            'email.unique'=>'Email này đã tồn tại',
            'password.required'=>'Bạn chưa nhập password',
            'password.min'=>'Độ dài mật khẩu tối thiểu 6 kí tự',
        ]);
    } 

    public function showLoginForm()
    {
        return view('auth.login');
    }
}
