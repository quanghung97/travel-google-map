<?php

namespace App\Repositories;

use App\Models\Trip;
use App\Repositories\Contracts\TripInterface;

class TripRepository extends BaseRepository implements TripInterface
{
    public function __construct(Trip $trip)
    {
        parent::__construct($trip);
    }
}
