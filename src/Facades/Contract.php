<?php

namespace Larathereum\Facades;

use Illuminate\Support\Facades\Facade;

class Contract extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'contract';
    }
}