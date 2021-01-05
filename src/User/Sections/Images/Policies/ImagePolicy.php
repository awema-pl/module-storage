<?php
namespace AwemaPL\Storage\User\Sections\Images\Policies;

use AwemaPL\Storage\User\Sections\Images\Models\Image;
use Illuminate\Foundation\Auth\User;

class ImagePolicy
{

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  User $user
     * @param  Image $image
     * @return bool
     */
    public function isOwner(User $user, Image $image)
    {
        return $user->id === $image->user_id;
    }


}
