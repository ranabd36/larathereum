<?php

namespace Larathereum\Facades;

use Illuminate\Support\Facades\Facade;

class Personal extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'personal';
    }
}