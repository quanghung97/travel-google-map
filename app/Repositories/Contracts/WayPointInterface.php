<?php

namespace App\Repositories\Contracts;

interface WayPointInterface
{
    /**
     * store waypoint Trip
     * @return  mixed
     */
    public function createMultiWayPoint($requestData, $tripId);

    /**
     * update arrival leave time Trip at point 0
     * @return  mixed
     */
    public function createLeaveArrivalTime($leave, $arrival, $tripId);
}
