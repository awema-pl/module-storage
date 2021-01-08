<?php

namespace AwemaPL\Storage\User\Sections\DuplicateProducts\Models\Contracts;

use Illuminate\Support\Collection;

interface DuplicateProduct
{
    /**
     * Get the user that owns the duplicate product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user();

    /**
     * Get the warehouse that owns the duplicate product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse();

    /**
     * Get the product that owns the duplicate product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product();
}
