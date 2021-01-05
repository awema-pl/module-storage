<?php
namespace AwemaPL\Storage\User\Sections\Descriptions\Policies;

use AwemaPL\Storage\User\Sections\Descriptions\Models\Description;
use Illuminate\Foundation\Auth\User;

class DescriptionPolicy
{

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  User $user
     * @param  Description $description
     * @return bool
     */
    public function isOwner(User $user, Description $description)
    {
        return $user->id === $description->user_id;
    }


}
