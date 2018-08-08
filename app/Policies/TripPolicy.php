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

    public function cantUpdateTrip(User $user, Trip $trip)
    {
        if ($user->id != $trip->owner_id) {
            return true;
        }
        return false;
    }

    public function follow(User $user, Trip $trip)
    {
        foreach ($user->tripsFollow as $tripFollow) {
            if ($tripFollow->id === $trip->id) {
                return true;
            }
        }
        return false;
    }

    public function join(User $user, Trip $trip)
    {
        foreach ($user->tripsJoin as $tripJoin) {
            if ($tripJoin->id === $trip->id) {
                return true;
            }
        }
        return false;
    }

    public function verify(User $user, Trip $trip)
    {
        foreach ($user->tripsVerify as $tripVerify) {
            if ($tripVerify->id === $trip->id) {
                return true;
            }
        }
        return false;
    }

    public function joinAble(User $user, Trip $trip)
    {
        foreach ($user->tripsVerify as $tripVerify) {
            if ($tripVerify->id === $trip->id) {
                return false;
            }
        }
        foreach ($user->tripsJoin as $tripJoin) {
            if ($tripJoin->id === $trip->id) {
                return false;
            }
        }
        return true;
    }

    public function ablePlan(User $user, Trip $trip)
    {
        if ($trip->status == 'planning') {
            return true;
        }
        return false;
    }
}
