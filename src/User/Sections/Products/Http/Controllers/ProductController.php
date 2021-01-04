<?php

namespace AwemaPL\Storage\User\Sections\Products\Http\Controllers;

use AwemaPL\Storage\User\Sections\Products\Models\Product;
use AwemaPL\Storage\Admin\Sections\Settings\Repositories\Contracts\SettingRepository;
use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Storage\User\Sections\Products\Http\Requests\StoreProduct;
use AwemaPL\Storage\User\Sections\Products\Http\Requests\UpdateProduct;
use AwemaPL\Storage\User\Sections\Products\Repositories\Contracts\ProductRepository;
use AwemaPL\Storage\User\Sections\Products\Resources\EloquentProduct;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProductController extends Controller
{
    use RedirectsTo, AuthorizesRequests;

    /** @var ProductRepository $products */
    protected $products;

    /** @var SettingRepository */
    protected $settings;

    public function __construct(ProductRepository $products, SettingRepository $settings)
    {
        $this->products = $products;
        $this->settings = $settings;
    }

    /**
     * Display products
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('storage::user.sections.products.index');
    }

    /**
     * Request scope
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function scope(Request $request)
    {
        return EloquentProduct::collection(
            $this->products->scope($request)
                ->isOwner()
                ->latest()->smartPaginate()
        );
    }

    /**
     * Create product
     *
     * @param StoreProduct $request
     * @return array
     * @throws \Exception
     */
    public function store(StoreProduct $request)
    {
          $this->products->create($request->all());
        return notify(_p('storage::notifies.user.product.success_added_product', 'Success added product.'));
    }

    /**
     * Update product
     *
     * @param UpdateProduct $request
     * @param $id
     * @return array
     */
    public function update(UpdateProduct $request, $id)
    {
        $this->authorize('isOwner', Product::find($id));
        $this->products->update($request->all(), $id);
        return notify(_p('storage::notifies.user.product.success_updated_product', 'Success updated product.'));
    }

    /**
     * Delete product
     *
     * @param $id
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete($id)
    {
        $this->authorize('isOwner', Product::find($id));
        $this->products->delete($id);
        return notify(_p('storage::notifies.user.product.success_deleted_product', 'Success deleted product.'));
    }

    /**
     * Select availability
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function selectAvailability(Request $request)
    {
        return $this->ajax($this->products->selectAvailability());
    }

    /**
     * Select product ID
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function selectProductId(Request $request)
    {
        return $this->ajax($this->products->selectProductId($request));
    }
}
