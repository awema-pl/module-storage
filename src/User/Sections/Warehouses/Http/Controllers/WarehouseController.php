<?php

namespace AwemaPL\Storage\User\Sections\Warehouses\Http\Controllers;

use AwemaPL\Storage\Client\Api\ConnectionValidator;
use AwemaPL\Storage\Client\Api\StorageApiException;
use AwemaPL\Storage\Client\StorageClient;
use AwemaPL\Storage\User\Sections\Warehouses\Models\Warehouse;
use AwemaPL\Storage\Admin\Sections\Settings\Repositories\Contracts\SettingRepository;
use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Storage\User\Sections\Warehouses\Http\Requests\StoreWarehouse;
use AwemaPL\Storage\User\Sections\Warehouses\Http\Requests\UpdateWarehouse;
use AwemaPL\Storage\User\Sections\Warehouses\Repositories\Contracts\WarehouseRepository;
use AwemaPL\Storage\User\Sections\Warehouses\Resources\EloquentWarehouse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WarehouseController extends Controller
{
    use RedirectsTo, AuthorizesRequests;

    /** @var WarehouseRepository $warehouses */
    protected $warehouses;

    /** @var SettingRepository */
    protected $settings;

    public function __construct(WarehouseRepository $warehouses, SettingRepository $settings)
    {
        $this->warehouses = $warehouses;
        $this->settings = $settings;
    }

    /**
     * Display warehouses
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('storage::user.sections.warehouses.index');
    }

    /**
     * Request scope
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function scope(Request $request)
    {
        return EloquentWarehouse::collection(
            $this->warehouses->scope($request)
                ->isOwner()
                ->latest()->smartPaginate()
        );
    }

    /**
     * Create warehouse
     *
     * @param StoreWarehouse $request
     * @return array
     * @throws \Exception
     */
    public function store(StoreWarehouse $request)
    {
          $this->warehouses->create($request->all());
        return notify(_p('storage::notifies.user.warehouse.success_added_warehouse', 'Success added warehouse.'));
    }

    /**
     * Update warehouse
     *
     * @param UpdateWarehouse $request
     * @param $id
     * @return array
     */
    public function update(UpdateWarehouse $request, $id)
    {
        $this->authorize('isOwner', Warehouse::find($id));
        $this->warehouses->update($request->all(), $id);
        return notify(_p('storage::notifies.user.warehouse.success_updated_warehouse', 'Success updated warehouse.'));
    }

    /**
     * Delete warehouse
     *
     * @param $id
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete($id)
    {
        $this->authorize('isOwner', Warehouse::find($id));
        $this->warehouses->delete($id);
        return notify(_p('storage::notifies.user.warehouse.success_deleted_warehouse', 'Success deleted warehouse.'));
    }

    /**
     * Select warehouse ID
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function selectWarehouseId(Request $request)
    {
        return $this->ajax($this->warehouses->selectWarehouseId($request));
    }
}
