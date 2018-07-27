<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\UpdateProfileRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Facades\UserRepository;

class ProfileController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = UserRepository::findOrFail($id);
        return view('user.profile.index', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProfileRequest $request, $id)
    {
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
