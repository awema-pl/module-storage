<?php

namespace AwemaPL\Storage\User\Sections\Categories\Scopes;

use AwemaPL\Repository\Scopes\ScopesAbstract;

class EloquentCategoryScopes extends ScopesAbstract
{
    protected $scopes = [
        'q' =>SearchCategory::class,
    ];
}
