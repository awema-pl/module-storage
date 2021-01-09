<?php

namespace AwemaPL\Storage\User\Sections\Sources\Scopes;
use AwemaPL\Repository\Scopes\ScopeAbstract;
use Illuminate\Database\Eloquent\Builder;

class SearchSource extends ScopeAbstract
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

        return $builder->whereHasMorph('sourceable', '*', function (Builder $query) use (&$value) {
            $query->where('name', 'like', '%' . $value . '%');
        });
    }
}
