<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfilePolicy
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

    public function updateProfile(User $user, User $userOther)
    {
        // Check if user is the user profile
        if ($user->id === $userOther->id) {
            return true;
        }

        return false;
    }
}
