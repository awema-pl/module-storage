<?php

namespace AwemaPL\Storage\User\Sections\Variants\Scopes;

use AwemaPL\Repository\Scopes\ScopesAbstract;

class EloquentVariantScopes extends ScopesAbstract
{
    protected $scopes = [
        'q' =>SearchVariant::class,
    ];
}
