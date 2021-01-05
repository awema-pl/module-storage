<?php

namespace AwemaPL\Storage\User\Sections\Descriptions\Models\Contracts;

use AwemaPL\Storage\User\Sections\Categories\Models\Category;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Description
{
    /**
     * Get the user that owns the description.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user();

    /**
     * Get the warehouse that owns the description.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse();

    /**
     * Get the product that owns the description.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product();
}
