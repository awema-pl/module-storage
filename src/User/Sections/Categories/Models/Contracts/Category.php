<?php

namespace AwemaPL\Storage\User\Sections\Categories\Models\Contracts;

interface Category
{
    /**
     * Get the user that owns the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user();

    /**
     * Get the warehouse that owns the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse();

    /**
     * Get the parent category that owns the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent();

    /**
     * Get crumbs
     *
     * @return string
     */
    public function crumbs();

    /**
     * Get parent crumbs
     *
     * @return string
     */
    public function parentCrumbs();
}
