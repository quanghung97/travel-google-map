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
        if (!empty($request->check_in))
        {
            //dd($request->check_in);
            //$binary_data = base64_decode( $request->check_in );
            $img = $request->check_in;
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $namephoto = uniqid().".png";
            //dd($namephoto);
            $result = file_put_contents( 'image/comment/'.$namephoto, $data );
            if (!$result) {
                return Redirect::back()->withErrors('errors', 'Could not save image! Check file permission');
            }

        }
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
