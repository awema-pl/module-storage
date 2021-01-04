<?php

namespace AwemaPL\Storage\User\Sections\Warehouses\Scopes;

use AwemaPL\Repository\Scopes\ScopesAbstract;

class EloquentWarehouseScopes extends ScopesAbstract
{
    protected $scopes = [
        'q' =>SearchWarehouse::class,
    ];
}
