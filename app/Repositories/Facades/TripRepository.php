<?php

namespace App\Repositories\Facades;

use Illuminate\Support\Facades\Facade;

class TripRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'App\\Repositories\\Contracts\\TripInterface';
    }
}
