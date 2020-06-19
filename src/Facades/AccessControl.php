<?php

namespace pierresilva\AccessControl\Facades;

use Illuminate\Support\Facades\Facade;

class AccessControl extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'access-control';
    }
}
