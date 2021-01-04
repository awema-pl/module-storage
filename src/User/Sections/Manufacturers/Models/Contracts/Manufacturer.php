<?php

namespace AwemaPL\Storage\User\Sections\Manufacturers\Models\Contracts;

interface Manufacturer
{
    /**
     * Get the user that owns the manufacturer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user();

    /**
     * Get the warehouse that owns the manufacturer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse();
}
