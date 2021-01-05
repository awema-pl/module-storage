<?php

namespace AwemaPL\Storage\User\Sections\Features\Http\Controllers;

use AwemaPL\Storage\User\Sections\Features\Models\Feature;
use AwemaPL\Storage\Admin\Sections\Settings\Repositories\Contracts\SettingRepository;
use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Storage\User\Sections\Features\Http\Requests\StoreFeature;
use AwemaPL\Storage\User\Sections\Features\Http\Requests\UpdateFeature;
use AwemaPL\Storage\User\Sections\Features\Repositories\Contracts\FeatureRepository;
use AwemaPL\Storage\User\Sections\Features\Resources\EloquentFeature;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FeatureController extends Controller
{
    use RedirectsTo, AuthorizesRequests;

    /** @var FeatureRepository $features */
    protected $features;

    /** @var SettingRepository */
    protected $settings;

    public function __construct(FeatureRepository $features, SettingRepository $settings)
    {
        $this->features = $features;
        $this->settings = $settings;
    }

    /**
     * Display features
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('storage::user.sections.features.index');
    }

    /**
     * Request scope
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function scope(Request $request)
    {
        return EloquentFeature::collection(
            $this->features->scope($request)
                ->isOwner()
                ->with(['warehouse', 'product', 'variant'])
                ->latest()->smartPaginate()
        );
    }

    /**
     * Create feature
     *
     * @param StoreFeature $request
     * @return array
     * @throws \Exception
     */
    public function store(StoreFeature $request)
    {
          $this->features->create($request->all());
        return notify(_p('storage::notifies.user.feature.success_added_feature', 'Success added feature.'));
    }

    /**
     * Update feature
     *
     * @param UpdateFeature $request
     * @param $id
     * @return array
     */
    public function update(UpdateFeature $request, $id)
    {
        $this->authorize('isOwner', Feature::find($id));
        $this->features->update($request->all(), $id);
        return notify(_p('storage::notifies.user.feature.success_updated_feature', 'Success updated feature.'));
    }

    /**
     * Delete feature
     *
     * @param $id
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete($id)
    {
        $this->authorize('isOwner', Feature::find($id));
        $this->features->delete($id);
        return notify(_p('storage::notifies.user.feature.success_deleted_feature', 'Success deleted feature.'));
    }

    /**
     * Select type
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function selectType(Request $request)
    {
        return $this->ajax($this->features->selectType());
    }
}
