<?php

namespace App\Repositories;

use App\Models\Trip;
use App\Repositories\Contracts\TripInterface;

class TripRepository extends BaseRepository implements TripInterface
{
    public function __construct(Trip $trip)
    {
        parent::__construct($trip);
    }

    public function getAllTripHotest()
    {
        $listFollowJoinUser = $this->model->withCount('usersFollow', 'usersJoin')
        ->orderByRaw('`users_follow_count` + `users_join_count`', 'desc')->get();
    }

    public function updateImage($id, $image)
    {
        $image_url = $this->model->findOrFail($id);
        $img_file_extension = $image->getClientOriginalExtension(); // Lấy đuôi của file hình ảnh

        if ($img_file_extension != 'PNG' && $img_file_extension != 'jpg' && $img_file_extension != 'jpeg' && $img_file_extension != 'png') {
            return Redirect::back()->withErrors(
                [ 'errors' => 'Định dạng hình ảnh không hợp lệ (chỉ hỗ trợ các định dạng: png, jpg, jpeg)!' ]
            );
        } else {
            $img_file_name = $image->getClientOriginalName(); // Lấy tên của file hình ảnh

            $random_file_name = str_random(4).'_'.$img_file_name; // Random tên file để tránh trường hợp trùng với tên hình ảnh khác trong CSDL
            while (file_exists('image/trip/'.$random_file_name)) { // Trường hợp trên gán với 4 ký tự random nhưng vẫn có thể xảy ra trường hợp bị trùng, nên bỏ vào vòng lặp while để kiểm tra với tên tất cả các file hình trong CSDL, nếu bị trùng thì sẽ random 1 tên khác đến khi nào ko trùng nữa thì thoát vòng lặp
                $random_file_name = str_random(4).'_'.$img_file_name;
            }

            $image->move('image/trip/', $random_file_name); // file hình được upload sẽ chuyển vào thư mục có đường dẫn như trên
            $image_url->image_url = 'image/trip/'.$random_file_name;
            $image_url->save();
        }
    }
}
