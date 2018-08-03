<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Repositories\Contracts\CommentInterface;
use Auth;

class CommentRepository extends BaseRepository implements CommentInterface
{
    public function __construct(Comment $comment)
    {
        parent::__construct($comment);
    }

    /**
     * This mean replycomment
     * @return mixed
     */
    public function storeComment($id, $content, $address)
    {
        $comment = new Comment();
        $comment->content = $content;
        $comment->user_id = Auth::user()->id;
        $comment->address = $address;

        Comment::findOrFail($id)->comments()->save($comment);
    }
}
