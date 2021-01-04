<?php

namespace AwemaPL\Storage\User\Sections\Products\Models\Contracts;

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
     * Get the category that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category();

    /**
     * Get the manufacturer that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manufacturer();

    /**
     * Assign categories products
     */
    public function assignCategoriesProducts();

}
