<?php

namespace AwemaPL\Storage\User\Sections\Variants\Models\Contracts;

use AwemaPL\Storage\User\Sections\Categories\Models\Category;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Variant
{
    /**
     * Get the user that owns the variant.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user();

    /**
     * Get the warehouse that owns the variant.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse();

    /**
     * Get the product that owns the variant.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product();
}
