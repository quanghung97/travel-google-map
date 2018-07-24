<?php

namespace App\Repositories;

use App\Models\WayPoint;
use App\Repositories\Contracts\TripInterface;

class WayPointRepository extends BaseRepository implements WayPointInterface
{
    public function __construct(WayPoint $waypoint)
    {
        parent::__construct($waypoint);
    }
}
