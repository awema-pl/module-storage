<?php
namespace AwemaPL\Storage\User\Sections\Variants\Policies;

use AwemaPL\Storage\User\Sections\Variants\Models\Variant;
use Illuminate\Foundation\Auth\User;

class VariantPolicy
{

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  User $user
     * @param  Variant $variant
     * @return bool
     */
    public function isOwner(User $user, Variant $variant)
    {
        return $user->id === $variant->user_id;
    }


}
