<?php

namespace App\Repositories;

use App\Models\Trip;
use App\Models\Comment;
use App\Repositories\Contracts\TripInterface;
use App\Repositories\Contracts\CommentInterface;
use Redirect;
use Auth;

class TripRepository extends BaseRepository implements TripInterface, CommentInterface
{
    public function __construct(Trip $trip)
    {
        parent::__construct($trip);
    }

    public function getTripHotest()
    {
        $listFollowJoinUser = $this->model->withCount('usersFollow', 'usersJoin', 'comments')
        ->orderBy('users_follow_count', 'DESC')->orderBy('users_join_count', 'DESC')->orderBy('comments_count', 'DESC')->limit(10)->get();
        return $listFollowJoinUser;
    }

    public function updateImage($id, $image)
    {
        $image_url = $this->model->findOrFail($id);
        $img_file_extension = $image->getClientOriginalExtension(); // Lấy đuôi của file hình ảnh

        if ($img_file_extension == 'PNG' || $img_file_extension == 'jpg' || $img_file_extension == 'jpeg' || $img_file_extension == 'png') {
            $img_file_name = $image->getClientOriginalName(); // Lấy tên của file hình ảnh

            $random_file_name = str_random(4).'_'.$img_file_name; // Random tên file để tránh trường hợp trùng với tên hình ảnh khác trong CSDL
            while (file_exists('image/trip/'.$random_file_name)) { // Trường hợp trên gán với 4 ký tự random nhưng vẫn có thể xảy ra trường hợp bị trùng, nên bỏ vào vòng lặp while để kiểm tra với tên tất cả các file hình trong CSDL, nếu bị trùng thì sẽ random 1 tên khác đến khi nào ko trùng nữa thì thoát vòng lặp
                $random_file_name = str_random(4).'_'.$img_file_name;
            }

            $image->move('image/trip/', $random_file_name); // file hình được upload sẽ chuyển vào thư mục có đường dẫn như trên
            $image_url->image_url = 'image/trip/'.$random_file_name;
            $image_url->save();
            return true;
        } else {
            $image_url->delete();
            return false;
        }
    }

    public function storeMultiTime($waypoint, $request)
    {
        for ($i = 0; $i < count($waypoint); $i++) {
            $action = 'action'.$i;
            $leave_time = 'leave_time'.$i;
            $arrival_time = 'arrival_time'.$i;
            $waypoint[$i]->action = $request->$action;
            $waypoint[$i]->leave_time = $request->$leave_time;
            $waypoint[$i]->arrival_time = $request->$arrival_time;
            $waypoint[$i]->save();
        }
    }

    public function storeComment($id, $content, $address)
    {
        $comment = new Comment();
        $comment->content = $content;
        $comment->user_id = Auth::user()->id;
        $comment->trip_id = $id;
        $comment->address = $address;

        Trip::findOrFail($id)->comments()->save($comment);
    }
}
