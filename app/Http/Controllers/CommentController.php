<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Facades\CommentRepository;
use App\Repositories\Facades\TripRepository;
use Redirect;
use Auth;

class CommentController extends Controller
{
    public function addTripComment(Request $request, $trip_id)
    {
        //dd($request->multiphoto);
        $this->validate($request, [
            'content'=>'required'
        ]);
        $multiphoto = $request->file('multiphoto');
        //dd($request);
        //dd($multiphoto);
        $bool = TripRepository::storeComment($trip_id, $request->content, $request->user_address, $request->check_in, $multiphoto);
        if ($bool == false) {
            return Redirect::back()->with('warning', 'Một số file bạn upload định dạng chưa đúng dạng hình ảnh (jpg, png....)');
        }
        return Redirect::back()->with('message', 'Comment thành công');
    }

    public function addReplyComment(Request $request, $comment_id)
    {
        $this->validate($request, [
            'content'=>'required'
        ]);
        CommentRepository::storeComment($comment_id, $request->content, $request->user_address, $request->check_in, $request->multiphoto);

        return Redirect::back()->with('message', 'Reply thành công');
    }

    public function update(Request $request, $comment_id)
    {
        $this->validate($request, [
            'content'=>'required'
        ]);
        $comment = CommentRepository::findOrFail($comment_id);
        $this->authorize('updateComment', $comment);
        //dd($request->content);
        $comment->update($request->all());
        return Redirect::back()->with('message', 'Sửa comment thành công');
        //dd($request);
    }

    public function destroy($id)
    {
        $comment = CommentRepository::findOrFail($id);
        $this->authorize('deleteComment', $comment);
        CommentRepository::findOrFail($id)->images()->delete();
        CommentRepository::destroy($id);

        return Redirect::back()->with('message', 'Xóa thành công comment');
    }
}
