<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Repositories\Contracts\CommentInterface;

class CommentRepository extends BaseRepository implements CommentInterface
{
    public function __construct(Comment $comment)
    {
        parent::__construct($comment);
    }
}
