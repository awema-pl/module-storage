<?php

namespace AwemaPL\Storage\User\Sections\Sources\Services\Contracts;

interface SourceType
{
    /**
     * Get types
     *
     * @return array
     */
    public function getTypes();

    /**
     * Get type
     *
     * @param $type
     * @return string
     */
    public function getType($type);

    /**
     * Get name
     *
     * @param $type
     * @return string
     */
    public function getName($type);

}
