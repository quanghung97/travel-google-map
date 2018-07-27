<?php

namespace App\Repositories\Contracts;

interface WayPointInterface
{
    /**
     * store waypoint Trip
     * @return  mixed
     */
    public function createMultiWayPoint($requestData, $tripId);
}
