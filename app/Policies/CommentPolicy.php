<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function updateComment(User $user, Comment $comment)
    {
        // Check if comment is the user owner
        if ($user->id === $comment->user_id) {
            return true;
        }

        return false;
    }
    public function deleteComment(User $user, Comment $comment)
    {
        if ($user->can('Access Admin')) {
            return true;
        }
        if ($user->id === $comment->user_id) {
            return true;
        }
        if ($user->id === $comment->trip->owner_id) {
            return true;
        }

        return false;
    }
}
