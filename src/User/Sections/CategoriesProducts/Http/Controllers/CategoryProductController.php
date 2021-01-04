<?php

namespace AwemaPL\Storage\User\Sections\CategoriesProducts\Http\Controllers;

use AwemaPL\Storage\User\Sections\CategoriesProducts\Models\CategoryProduct;
use AwemaPL\Storage\Admin\Sections\Settings\Repositories\Contracts\SettingRepository;
use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Storage\User\Sections\CategoriesProducts\Http\Requests\StoreCategoryProduct;
use AwemaPL\Storage\User\Sections\CategoriesProducts\Http\Requests\UpdateCategoryProduct;
use AwemaPL\Storage\User\Sections\CategoriesProducts\Repositories\Contracts\CategoryProductRepository;
use AwemaPL\Storage\User\Sections\CategoriesProducts\Resources\EloquentCategoryProduct;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CategoryProductController extends Controller
{
    use RedirectsTo, AuthorizesRequests;

    /** @var CategoryProductRepository $categoriesProducts */
    protected $categoriesProducts;

    /** @var SettingRepository */
    protected $settings;

    public function __construct(CategoryProductRepository $categoriesProducts, SettingRepository $settings)
    {
        $this->categoriesProducts = $categoriesProducts;
        $this->settings = $settings;
    }

    /**
     * Display categories products
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('storage::user.sections.categories-products.index');
    }

    /**
     * Request scope
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function scope(Request $request)
    {
        return EloquentCategoryProduct::collection(
            $this->categoriesProducts->scope($request)
                ->isOwner()
                ->latest()->smartPaginate()
        );
    }

    /**
     * Create category product
     *
     * @param StoreCategoryProduct $request
     * @return array
     * @throws \Exception
     */
    public function store(StoreCategoryProduct $request)
    {
          $this->categoriesProducts->create($request->all());
        return notify(_p('storage::notifies.user.category_product.success_added_category_product', 'Success added category product.'));
    }

    /**
     * Update category product
     *
     * @param UpdateCategoryProduct $request
     * @param $id
     * @return array
     */
    public function update(UpdateCategoryProduct $request, $id)
    {
        $this->authorize('isOwner', CategoryProduct::find($id));
        $this->categoriesProducts->update($request->all(), $id);
        return notify(_p('storage::notifies.user.category_product.success_updated_category_product', 'Success updated category product.'));
    }

    /**
     * Delete category product
     *
     * @param $id
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete($id)
    {
        $this->authorize('isOwner', CategoryProduct::find($id));
        $this->categoriesProducts->delete($id);
        return notify(_p('storage::notifies.user.category_product.success_deleted_category_product', 'Success deleted category product.'));
    }
}
