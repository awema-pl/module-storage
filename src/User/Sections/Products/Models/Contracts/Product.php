<?php

namespace AwemaPL\Storage\User\Sections\Products\Models\Contracts;

use AwemaPL\Storage\User\Sections\Categories\Models\Category;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface Product
{
    /**
     * Get the user that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user();

    /**
     * Get the warehouse that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse();

    /**
     * Get the default category that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function defaultCategory();

    /**
     * Get the manufacturer that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manufacturer();

    /**
     * The categories that belong to the product.
     *
     * @return BelongsToMany
     */
    public function categories();

    /**
     * Get all of the descriptions for the product.
     *
     * @return HasMany
     */
    public function descriptions();


    /**
     * Get all of the variants for the product.
     *
     * @return HasMany
     */
    public function variants();

    /**
     * Get all of the images for the product.
     *
     * @return HasMany
     */
    public function images();

    /**
     * Get all of the features for the product.
     *
     * @return HasMany
     */
    public function features();
}
