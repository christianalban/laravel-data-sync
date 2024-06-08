<?php

namespace Alban\LaravelDataSync\Facades;

use Illuminate\Support\Facades\Facade;

class DataSync extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'data-sync';
    }
}
