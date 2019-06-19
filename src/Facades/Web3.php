<?php

namespace Larathereum\Facades;

use Illuminate\Support\Facades\Facade;

class Web3 extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'web3';
    }
}