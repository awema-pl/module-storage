<?php

namespace AwemaPL\Storage\User\Sections\Manufacturers\Scopes;

use AwemaPL\Repository\Scopes\ScopesAbstract;

class EloquentManufacturerScopes extends ScopesAbstract
{
    protected $scopes = [
        'q' =>SearchManufacturer::class,
    ];
}
