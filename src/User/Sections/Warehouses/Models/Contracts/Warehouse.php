<?php

namespace AwemaPL\Storage\User\Sections\Warehouses\Models\Contracts;

use AwemaPL\Task\User\Sections\Statuses\Models\Contracts\Taskable;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface Warehouse extends Taskable
{
    /**
     * Get all of the products for the product.
     *
     * @return HasMany
     */
    public function products();
}
