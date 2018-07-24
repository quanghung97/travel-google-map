<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Facades\UserRepository;
use File;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = UserRepository::findOrFail($id);
        return view('user.profile.index', ['user'=>$user]);
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

    public function checkChangeProfile(Request $request, $id)
    {
        // dd($request);
        $this->validate(
            $request,
            [
                'name' => 'required',
            ],
            [
                'name.required' => 'Bạn chưa nhập Tên!',
            ]
        );
        if ($request->has('password')) {
            $this->validate(
                $request,
                [
                    'password' => 'required|min:6|max:32',
                    'password_again' => 'required|same:password'
                ],
                [
                    'password.required' => 'Bạn chưa nhập mật khẩu!',
                    'password.min' => 'Mật khẩu gồm tối thiểu 6 ký tự!',
                    'password.max' => 'Mật khẩu không được vượt quá 32 ký tự!',
                    'password_again.required' => 'Bạn chưa xác nhận mật khẩu!',
                    'password_again.same' => 'Mật khẩu xác nhận chưa khớp với mật khẩu đã nhập!'
                ]
            );
        }
        if ($request->hasFile('file')) {// Kiểm tra xem người dùng có upload hình hay không
            $img_file = $request->file('file');
            UserRepository::updateAvatar($id, $img_file);
        }
        $user = UserRepository::findOrFail($id);
        $requestData = $request->all();
        $user->update($requestData);
        return redirect()->back()->with('message', 'Thay Đổi thông tin Người Dùng thành công!');
    }
}
