<?php

namespace AwemaPL\Storage\User\Sections\Sources\Models\Contracts;

interface Sourceable
{
    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get key
     *
     * @return mixed
     */
    public function getKey();

    /**
     * Import products
     *
     * @param array $options
     */
    public function importProducts($options=[]): void;

    /**
     * Update products
     *
     * @param array $options
     */
    public function updateProducts($options=[]): void;
}
