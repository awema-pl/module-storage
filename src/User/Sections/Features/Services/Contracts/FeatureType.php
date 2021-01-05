<?php

namespace AwemaPL\Storage\User\Sections\Features\Services\Contracts;

interface FeatureType
{
    /**
     * Get types
     *
     * @return array
     */
    public function getTypes():array;

    /**
     * Get type name
     *
     * @param $value
     * @return string
     */
    public function getTypeName($value):string;
}
