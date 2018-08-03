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
        $this->validate($request, [
            'content'=>'required'
        ]);

        TripRepository::storeComment($trip_id, $request->content, $request->user_address);

        return Redirect::back()->with('message', 'Comment thành công');
    }

    public function addReplyComment(Request $request, $comment_id)
    {
        $this->validate($request, [
            'content'=>'required'
        ]);
        CommentRepository::storeComment($comment_id, $request->content, $request->user_address);

        return Redirect::back()->with('message', 'Reply thành công');
    }

    public function update(Request $request)
    {
    }

    public function destroy(Request $request)
    {
    }
}
