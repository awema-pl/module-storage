<?php

namespace AwemaPL\Storage\User\Sections\Sources\Models\Contracts;

interface Source
{
    /**
     * Get key
     *
     * @return mixed
     */
    public function getKey();

    /**
     * Get the owning sourceable model.
     */
    public function sourceable();

    /**
     * Get the warehouse that owns the source.
     */
    public function warehouse();
}
