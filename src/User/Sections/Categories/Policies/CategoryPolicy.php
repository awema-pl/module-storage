<?php
namespace AwemaPL\Storage\User\Sections\Categories\Policies;

use AwemaPL\Storage\User\Sections\Categories\Models\Category;
use Illuminate\Foundation\Auth\User;

class CategoryPolicy
{

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  User $user
     * @param  Category $category
     * @return bool
     */
    public function isOwner(User $user, Category $category)
    {
        return $user->id === $category->user_id;
    }


}
