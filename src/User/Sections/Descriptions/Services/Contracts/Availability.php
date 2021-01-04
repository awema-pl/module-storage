<?php

namespace AwemaPL\Storage\User\Sections\Products\Services\Contracts;
interface Availability
{
    /**
     * Get availabilities
     *
     * @return array
     */
    public function getAvailabilities():array;

    /**
     * Get default
     *
     * @return string
     */
    public function getDefault(): string;

    /**
     * Get availability name
     *
     * @param $value
     * @return string
     */
    public function getAvailabilityName($value):string;
}
