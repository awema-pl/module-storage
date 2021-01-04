<?php
namespace AwemaPL\Storage\User\Sections\Products\Policies;

use AwemaPL\Storage\User\Sections\Products\Models\Product;
use Illuminate\Foundation\Auth\User;

class ProductPolicy
{

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  User $user
     * @param  Product $product
     * @return bool
     */
    public function isOwner(User $user, Product $product)
    {
        return $user->id === $product->user_id;
    }


}
