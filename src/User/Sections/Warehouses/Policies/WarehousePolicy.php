<?php
namespace AwemaPL\Storage\User\Sections\Warehouses\Policies;

use AwemaPL\Storage\User\Sections\Warehouses\Models\Warehouse;
use Illuminate\Foundation\Auth\User;

class WarehousePolicy
{

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  User $user
     * @param  Warehouse $warehouse
     * @return bool
     */
    public function isOwner(User $user, Warehouse $warehouse)
    {
        return $user->id === $warehouse->user_id;
    }


}
