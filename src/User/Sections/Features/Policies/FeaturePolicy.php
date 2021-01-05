<?php
namespace AwemaPL\Storage\User\Sections\Features\Policies;

use AwemaPL\Storage\User\Sections\Features\Models\Feature;
use Illuminate\Foundation\Auth\User;

class FeaturePolicy
{

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  User $user
     * @param  Feature $feature
     * @return bool
     */
    public function isOwner(User $user, Feature $feature)
    {
        return $user->id === $feature->user_id;
    }


}
