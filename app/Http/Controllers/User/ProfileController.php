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
        return view('user.profile.index',['user'=>$user]);
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

    public function checkChangeProfile(Request $request,$id){
        // dd($request);
        $this->validate($request,
    		[
				'name' => 'required',
    		],
    		[
    			'name.required' => 'Bạn chưa nhập Tên!',
    		]);

    	$user = UserRepository::findOrFail($id);
    	$user->name = $request->name;
    	if($request->has('password'))
    	{
    		$this->validate($request,
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
    		]);
    		$user->password = Hash::make($request->password_again);
        }
        if( $request->hasFile('file')) // Kiểm tra xem người dùng có upload hình hay không
    	{
    		$img_file = $request->file('file'); // Nhận file hình ảnh người dùng upload lên server
    		
    		$img_file_extension = $img_file->getClientOriginalExtension(); // Lấy đuôi của file hình ảnh

    		if($img_file_extension != 'PNG' && $img_file_extension != 'jpg' && $img_file_extension != 'jpeg' && $img_file_extension != 'png')
    		{
    			return redirect()->back()->with('error','Định dạng hình ảnh không hợp lệ (chỉ hỗ trợ các định dạng: png, jpg, jpeg)!');
    		}
            else{
                $img_file_name = $img_file->getClientOriginalName(); // Lấy tên của file hình ảnh

    		    $random_file_name = str_random(4).'_'.$img_file_name; // Random tên file để tránh trường hợp trùng với tên hình ảnh khác trong CSDL
    		    while(file_exists('avatar/'.$random_file_name)) // Trường hợp trên gán với 4 ký tự random nhưng vẫn có thể xảy ra trường hợp bị trùng, nên bỏ vào vòng lặp while để kiểm tra với tên tất cả các file hình trong CSDL, nếu bị trùng thì sẽ random 1 tên khác đến khi nào ko trùng nữa thì thoát vòng lặp
    		    {
    			    $random_file_name = str_random(4).'_'.$img_file_name;
    		    }

    		    $img_file->move('avatar/',$random_file_name); // file hình được upload sẽ chuyển vào thư mục có đường dẫn như trên
    		    $user->g_avatar_url = 'avatar/'.$random_file_name;
            }
    		
    	}
    	else
            $user->g_avatar_url = 'avatar/defaut_avt.jpg'; // Nếu người dùng không upload hình thì sẽ gán đường dẫn là rỗng

        $user->save();
        return redirect()->back()->with('message','Thay Đổi thông tin Người Dùng thành công!');
    }

}
