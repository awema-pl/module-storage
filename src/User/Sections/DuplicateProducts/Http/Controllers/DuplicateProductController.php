<?php

namespace AwemaPL\Storage\User\Sections\DuplicateProducts\Http\Controllers;

use AwemaPL\Storage\User\Sections\DuplicateProducts\Jobs\GenerateDuplicateProductsJob;
use AwemaPL\Storage\User\Sections\DuplicateProducts\Models\DuplicateProduct;
use AwemaPL\Storage\Admin\Sections\Settings\Repositories\Contracts\SettingRepository;
use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Storage\User\Sections\DuplicateProducts\Http\Requests\StoreDuplicateProduct;
use AwemaPL\Storage\User\Sections\DuplicateProducts\Http\Requests\UpdateDuplicateProduct;
use AwemaPL\Storage\User\Sections\DuplicateProducts\Repositories\Contracts\DuplicateProductRepository;
use AwemaPL\Storage\User\Sections\DuplicateProducts\Resources\EloquentDuplicateProduct;
use AwemaPL\Storage\User\Sections\DuplicateProducts\Services\Contracts\ProductDuplicateGenerator;
use AwemaPL\Storage\User\Sections\Products\Models\Product;
use AwemaPL\Storage\User\Sections\Products\Repositories\Contracts\ProductRepository;
use AwemaPL\Storage\User\Sections\Warehouses\Models\Contracts\Warehouse;
use AwemaPL\Storage\User\Sections\Warehouses\Repositories\Contracts\WarehouseRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DuplicateProductController extends Controller
{
    use RedirectsTo, AuthorizesRequests;

    /** @var DuplicateProductRepository $duplicateProducts */
    protected $duplicateProducts;

    /** @var ProductRepository $products */
    protected $products;

    /** @var WarehouseRepository $warehouses */
    protected $warehouses;

    /** @var SettingRepository */
    protected $settings;

    /** @var ProductDuplicateGenerator $generator */
    protected $generator;

    public function __construct(DuplicateProductRepository $duplicateProducts, ProductRepository $products, WarehouseRepository $warehouses, SettingRepository $settings, ProductDuplicateGenerator $generator)
    {
        $this->duplicateProducts = $duplicateProducts;
        $this->products = $products;
        $this->warehouses = $warehouses;
        $this->settings = $settings;
        $this->generator = $generator;
    }

    /**
     * Display duplicate products
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('storage::user.sections.duplicate-products.index');
    }

    /**
     * Request scope
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function scope(Request $request)
    {
        return EloquentDuplicateProduct::collection(
            $this->duplicateProducts->scope($request)
                ->isOwner()
                 ->with('warehouse')
                ->latest()->smartPaginate()
        );
    }

    /**
     * Create duplicate product
     *
     * @param StoreDuplicateProduct $request
     * @return array
     * @throws \Exception
     */
    public function store(StoreDuplicateProduct $request)
    {
          $this->duplicateProducts->create($request->all());
        return notify(_p('storage::notifies.user.duplicate_product.success_added_duplicate_product', 'Success added duplicate product.'));
    }

    /**
     * Update duplicate product
     *
     * @param UpdateDuplicateProduct $request
     * @param $id
     * @return array
     */
    public function update(UpdateDuplicateProduct $request, $id)
    {
        $this->authorize('isOwner', DuplicateProduct::find($id));
        $this->duplicateProducts->update($request->all(), $id);
        return notify(_p('storage::notifies.user.duplicate_product.success_updated_duplicate_product', 'Success updated duplicate product.'));
    }

    /**
     * Delete duplicate product
     *
     * @param $id
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete($id)
    {
        $this->authorize('isOwner', DuplicateProduct::find($id));
        $this->duplicateProducts->delete($id);
        return notify(_p('storage::notifies.user.duplicate_product.success_deleted_duplicate_product', 'Success deleted duplicate product.'));
    }

    /**
     * Generate duplicate by product
     *
     * @param $id
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function generateDuplicateByProduct($id)
    {
        $product = $this->products->find($id);
        $this->authorize('isOwner', $product);
        $this->generator->generate($product);
        return notify(_p('storage::notifies.user.duplicate_product.success_generated_duplicate_product', 'Successfully generated duplicate product.'));
    }

    /**
     * Generate duplicate by warehouse
     *
     * @param $id
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function generateDuplicateByWarehouse($id)
    {
        /** @var Warehouse $warehouse */
        $warehouse = $this->warehouses->find($id);
        $this->authorize('isOwner', $warehouse);
        dispatch(new GenerateDuplicateProductsJob($warehouse));
        return notify(_p('storage::notifies.user.duplicate_product.success_started_duplicat_generation', 'Duplicate generation started successfully.'));
    }
}
