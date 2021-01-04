<?php

namespace AwemaPL\Storage\User\Sections\Manufacturers\Http\Controllers;

use AwemaPL\Storage\User\Sections\Manufacturers\Models\Manufacturer;
use AwemaPL\Storage\Admin\Sections\Settings\Repositories\Contracts\SettingRepository;
use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Storage\User\Sections\Manufacturers\Http\Requests\StoreManufacturer;
use AwemaPL\Storage\User\Sections\Manufacturers\Http\Requests\UpdateManufacturer;
use AwemaPL\Storage\User\Sections\Manufacturers\Repositories\Contracts\ManufacturerRepository;
use AwemaPL\Storage\User\Sections\Manufacturers\Resources\EloquentManufacturer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ManufacturerController extends Controller
{
    use RedirectsTo, AuthorizesRequests;

    /** @var ManufacturerRepository $manufacturers */
    protected $manufacturers;

    /** @var SettingRepository */
    protected $settings;

    public function __construct(ManufacturerRepository $manufacturers, SettingRepository $settings)
    {
        $this->manufacturers = $manufacturers;
        $this->settings = $settings;
    }

    /**
     * Display manufacturers
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('storage::user.sections.manufacturers.index');
    }

    /**
     * Request scope
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function scope(Request $request)
    {
        return EloquentManufacturer::collection(
            $this->manufacturers->scope($request)
                ->isOwner()
                ->latest()->smartPaginate()
        );
    }

    /**
     * Create manufacturer
     *
     * @param StoreManufacturer $request
     * @return array
     * @throws \Exception
     */
    public function store(StoreManufacturer $request)
    {
          $this->manufacturers->create($request->all());
        return notify(_p('storage::notifies.user.manufacturer.success_added_manufacturer', 'Success added manufacturer.'));
    }

    /**
     * Update manufacturer
     *
     * @param UpdateManufacturer $request
     * @param $id
     * @return array
     */
    public function update(UpdateManufacturer $request, $id)
    {
        $this->authorize('isOwner', Manufacturer::find($id));
        $this->manufacturers->update($request->all(), $id);
        return notify(_p('storage::notifies.user.manufacturer.success_updated_manufacturer', 'Success updated manufacturer.'));
    }

    /**
     * Delete manufacturer
     *
     * @param $id
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete($id)
    {
        $this->authorize('isOwner', Manufacturer::find($id));
        $this->manufacturers->delete($id);
        return notify(_p('storage::notifies.user.manufacturer.success_deleted_manufacturer', 'Success deleted manufacturer.'));
    }

    /**
     * Select manufacturer ID
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function selectManufacturerId(Request $request)
    {
        return $this->ajax($this->manufacturers->selectManufacturerId($request));
    }
}
