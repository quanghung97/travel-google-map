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
     * @return  mixed
     */
    public function updateImage($id, $img_file);
}
