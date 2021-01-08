<?php

namespace AwemaPL\Storage\User\Sections\Sources\Models\Contracts;

use AwemaPL\Storage\User\Sections\Sources\Models\Contracts\Source as SourceContract;

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
     * @param SourceContract $source
     * @param array $options
     */
    public function importProducts(SourceContract $source, $options=[]): void;

    /**
     * Update products
     *
     * @param SourceContract $source
     * @param array $options
     */
    public function updateProducts(SourceContract $source, $options=[]): void;
}
