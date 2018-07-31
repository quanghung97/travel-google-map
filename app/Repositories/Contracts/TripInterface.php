<?php

namespace App\Repositories\Contracts;

interface TripInterface
{
    /**
     * get all Trips hotest with total follow and join order by desc
     * @return  mixed
     */
    public function getAllTripHotest();

    /**
     * store image Trip
     * @return  bool
     */
    public function updateImage($id, $image);

    /**
     * store multiple time in trip
     * @return  mixed
     */
    public function storeMultiTime($waypoint, $request);
}
