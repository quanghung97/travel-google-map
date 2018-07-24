<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserInterface;

class UserRepository extends BaseRepository implements UserInterface
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function findOrCreateUser($user)
    {
        $authUser = $this->model->where('g_id', $user->getId())->first();
        if ($authUser) {
            return $authUser;
        }
        return $this->model->create([
            'name'     => $user->getName(),
            'email'    => $user->getEmail(),
            'g_id' => $user->getId(),
            'g_avatar_url' => $user->getAvatar(),
        ]);
    }

    public function getAllPermission($id)
    {
        return $this->model->findOrFail($id)->getPermissionsViaRoles()->pluck('name')->unique()->toArray();
    }

    public function updateAvatar($id, $image)
    {
        $image_url = $this->model->findOrFail($id);
        $img_file_extension = $image->getClientOriginalExtension(); // Lấy đuôi của file hình ảnh

        if ($img_file_extension != 'PNG' && $img_file_extension != 'jpg' && $img_file_extension != 'jpeg' && $img_file_extension != 'png') {
            return redirect()->back()->with('error', 'Định dạng hình ảnh không hợp lệ (chỉ hỗ trợ các định dạng: png, jpg, jpeg)!');
        } else {
            $img_file_name = $image->getClientOriginalName(); // Lấy tên của file hình ảnh

            $random_file_name = str_random(4).'_'.$img_file_name; // Random tên file để tránh trường hợp trùng với tên hình ảnh khác trong CSDL
            while (file_exists('avatar/'.$random_file_name)) { // Trường hợp trên gán với 4 ký tự random nhưng vẫn có thể xảy ra trường hợp bị trùng, nên bỏ vào vòng lặp while để kiểm tra với tên tất cả các file hình trong CSDL, nếu bị trùng thì sẽ random 1 tên khác đến khi nào ko trùng nữa thì thoát vòng lặp
                $random_file_name = str_random(4).'_'.$img_file_name;
            }

            $image->move('avatar/', $random_file_name); // file hình được upload sẽ chuyển vào thư mục có đường dẫn như trên
            $image_url->g_avatar_url = 'avatar/'.$random_file_name;
            $image_url->save();
        }
    }
}
