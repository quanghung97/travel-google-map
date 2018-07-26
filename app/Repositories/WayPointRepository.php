<?php

namespace App\Repositories;

use App\Models\WayPoint;
use App\Repositories\Contracts\WayPointInterface;

class WayPointRepository extends BaseRepository implements WayPointInterface
{
    public function __construct(WayPoint $waypoint)
    {
        parent::__construct($waypoint);
    }

    public function createMultiWayPoint($requestData, $tripId)
    {
        for ($i = 0; $i < count($requestData); $i = $i + 4) {
            for ($j = $i; $j < $i + 4; $j++) {
                if (isset($requestData['order_num'.$j])) {
                    WayPoint::create([
                        'order_num' => $requestData['order_num'.$j],
                        'lat' => $requestData['lat'.$j],
                        'lng' => $requestData['lng'.$j],
                        'address' => $requestData['address'.$j],
                        'trip_id' => $tripId,
                    ]);
                }
            }
        }
    }
}
