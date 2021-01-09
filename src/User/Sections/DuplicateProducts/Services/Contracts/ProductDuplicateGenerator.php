<?php

namespace AwemaPL\Storage\User\Sections\DuplicateProducts\Services\Contracts;
use AwemaPL\Storage\User\Sections\Warehouses\Models\Contracts\Warehouse;
use Illuminate\Database\Eloquent\Model;

interface ProductDuplicateGenerator
{
    /**
     * Generate warehouse
     *
     * @param Warehouse $warehouse
     */
    public function generateWarehouse(Warehouse $warehouse);

    /**
     * Generate
     *
     * @param Model $product
     */
    public function generate(Model $product):void;
}
