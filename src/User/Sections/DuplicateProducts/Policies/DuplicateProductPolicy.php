<?php
namespace AwemaPL\Storage\User\Sections\DuplicateProducts\Policies;

use AwemaPL\Storage\User\Sections\DuplicateProducts\Models\DuplicateProduct;
use Illuminate\Foundation\Auth\User;

class DuplicateProductPolicy
{

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  User $user
     * @param  DuplicateProduct $duplicateProduct
     * @return bool
     */
    public function isOwner(User $user, DuplicateProduct $duplicateProduct)
    {
        return $user->id === $duplicateProduct->user_id;
    }


}
