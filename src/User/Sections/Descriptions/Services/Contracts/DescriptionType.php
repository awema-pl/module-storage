<?php

namespace AwemaPL\Storage\User\Sections\Descriptions\Services\Contracts;

interface DescriptionType
{
    /**
     * Get types
     *
     * @return array
     */
    public function getTypes():array;

    /**
     * Get default
     *
     * @return string
     */
    public function getDefault(): string;

    /**
     * Get type name
     *
     * @param $value
     * @return string
     */
    public function getTypeName($value):string;
}
