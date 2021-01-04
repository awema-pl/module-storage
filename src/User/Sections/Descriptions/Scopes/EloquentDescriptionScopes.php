<?php

namespace AwemaPL\Storage\User\Sections\Products\Scopes;

use AwemaPL\Repository\Scopes\ScopesAbstract;

class EloquentProductScopes extends ScopesAbstract
{
    protected $scopes = [
        'q' =>SearchProduct::class,
    ];
}
