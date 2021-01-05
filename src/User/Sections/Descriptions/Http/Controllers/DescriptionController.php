<?php

namespace AwemaPL\Storage\User\Sections\Descriptions\Http\Controllers;

use AwemaPL\Storage\User\Sections\Descriptions\Models\Description;
use AwemaPL\Storage\Admin\Sections\Settings\Repositories\Contracts\SettingRepository;
use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Storage\User\Sections\Descriptions\Http\Requests\StoreDescription;
use AwemaPL\Storage\User\Sections\Descriptions\Http\Requests\UpdateDescription;
use AwemaPL\Storage\User\Sections\Descriptions\Repositories\Contracts\DescriptionRepository;
use AwemaPL\Storage\User\Sections\Descriptions\Resources\EloquentDescription;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DescriptionController extends Controller
{
    use RedirectsTo, AuthorizesRequests;

    /** @var DescriptionRepository $descriptions */
    protected $descriptions;

    /** @var SettingRepository */
    protected $settings;

    public function __construct(DescriptionRepository $descriptions, SettingRepository $settings)
    {
        $this->descriptions = $descriptions;
        $this->settings = $settings;
    }

    /**
     * Display descriptions
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('storage::user.sections.descriptions.index');
    }

    /**
     * Request scope
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function scope(Request $request)
    {
        return EloquentDescription::collection(
            $this->descriptions->scope($request)
                ->isOwner()
                ->with(['warehouse', 'product'])
                ->latest()->smartPaginate()
        );
    }

    /**
     * Create description
     *
     * @param StoreDescription $request
     * @return array
     * @throws \Exception
     */
    public function store(StoreDescription $request)
    {
          $this->descriptions->create($request->all());
        return notify(_p('storage::notifies.user.description.success_added_description', 'Success added description.'));
    }

    /**
     * Update description
     *
     * @param UpdateDescription $request
     * @param $id
     * @return array
     */
    public function update(UpdateDescription $request, $id)
    {
        $this->authorize('isOwner', Description::find($id));
        $this->descriptions->update($request->all(), $id);
        return notify(_p('storage::notifies.user.description.success_updated_description', 'Success updated description.'));
    }

    /**
     * Delete description
     *
     * @param $id
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete($id)
    {
        $this->authorize('isOwner', Description::find($id));
        $this->descriptions->delete($id);
        return notify(_p('storage::notifies.user.description.success_deleted_description', 'Success deleted description.'));
    }

    /**
     * Select type
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function selectType(Request $request)
    {
        return $this->ajax($this->descriptions->selectType());
    }
}
