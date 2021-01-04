<?php
namespace AwemaPL\Storage\User\Sections\Manufacturers\Policies;

use AwemaPL\Storage\User\Sections\Manufacturers\Models\Manufacturer;
use Illuminate\Foundation\Auth\User;

class ManufacturerPolicy
{

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  User $user
     * @param  Manufacturer $manufacturer
     * @return bool
     */
    public function isOwner(User $user, Manufacturer $manufacturer)
    {
        return $user->id === $manufacturer->user_id;
    }


}
