<?php

namespace AwemaPL\Storage\User\Sections\Features\Models\Contracts;

use AwemaPL\Storage\User\Sections\Categories\Models\Category;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Feature
{
    /**
     * Get the user that owns the feature.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user();

    /**
     * Get the warehouse that owns the feature.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse();

    /**
     * Get the product that owns the feature.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product();

    /**
     * Get the variant that owns the feature.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function variant();
}
