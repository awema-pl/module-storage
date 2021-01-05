<?php

namespace AwemaPL\Storage\User\Sections\Variants\Scopes;
use AwemaPL\Repository\Scopes\ScopeAbstract;

class SearchVariant extends ScopeAbstract
{
    /**
     * Scope
     *
     * @param $builder
     * @param $value
     * @param $scope
     * @return mixed
     */
    public function scope($builder, $value, $scope)
    {
        if (!$value){
            return $builder;
        }

        return $builder->where('name', 'like', '%'.$value.'%');
    }
}
