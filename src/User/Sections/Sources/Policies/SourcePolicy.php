<?php
namespace AwemaPL\Storage\User\Sections\Sources\Policies;

use AwemaPL\Storage\User\Sections\Sources\Models\Source;
use Illuminate\Foundation\Auth\User;

class SourcePolicy
{

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  User $user
     * @param  Source $source
     * @return bool
     */
    public function isOwner(User $user, Source $source)
    {
        return $user->id === $source->user_id;
    }


}
