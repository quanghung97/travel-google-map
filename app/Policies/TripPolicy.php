<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Trip;
use Illuminate\Auth\Access\HandlesAuthorization;

class TripPolicy
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

    public function updateTrip(User $user, Trip $trip)
    {
        // Check if user is the trip owner
        if ($user->id === $trip->owner_id) {
            return true;
        }

        return false;
    }
}
