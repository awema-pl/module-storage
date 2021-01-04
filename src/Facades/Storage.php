<?php

namespace AwemaPL\Storage\Facades;

use AwemaPL\Storage\Contracts\Storage as StorageContract;
use Illuminate\Support\Facades\Facade;

class Storage extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return StorageContract::class;
    }
}
