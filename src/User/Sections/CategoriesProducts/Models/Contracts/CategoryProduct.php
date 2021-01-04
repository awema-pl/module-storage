<?php

namespace AwemaPL\Storage\User\Sections\CategoriesProducts\Models\Contracts;

interface CategoryProduct
{
    /**
     * Get the user that owns the category product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user();

    /**
     * Get the warehouse that owns the category product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse();

    /**
     * Get the category that owns the category product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category();

    /**
     * Get the product that owns the category product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product();
}
