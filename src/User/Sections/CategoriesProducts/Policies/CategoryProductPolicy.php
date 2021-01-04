<?php
namespace AwemaPL\Storage\User\Sections\CategoriesProducts\Policies;

use AwemaPL\Storage\User\Sections\CategoriesProducts\Models\CategoryProduct;
use Illuminate\Foundation\Auth\User;

class CategoryProductPolicy
{

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  User $user
     * @param  CategoryProduct $categoryProduct
     * @return bool
     */
    public function isOwner(User $user, CategoryProduct $categoryProduct)
    {
        return $user->id === $categoryProduct->user_id;
    }


}
