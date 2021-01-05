<?php

namespace AwemaPL\Storage\User\Sections\Variants\Http\Controllers;

use AwemaPL\Storage\User\Sections\Variants\Models\Variant;
use AwemaPL\Storage\Admin\Sections\Settings\Repositories\Contracts\SettingRepository;
use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Storage\User\Sections\Variants\Http\Requests\StoreVariant;
use AwemaPL\Storage\User\Sections\Variants\Http\Requests\UpdateVariant;
use AwemaPL\Storage\User\Sections\Variants\Repositories\Contracts\VariantRepository;
use AwemaPL\Storage\User\Sections\Variants\Resources\EloquentVariant;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class VariantController extends Controller
{
    use RedirectsTo, AuthorizesRequests;

    /** @var VariantRepository $variants */
    protected $variants;

    /** @var SettingRepository */
    protected $settings;

    public function __construct(VariantRepository $variants, SettingRepository $settings)
    {
        $this->variants = $variants;
        $this->settings = $settings;
    }

    /**
     * Display variants
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('storage::user.sections.variants.index');
    }

    /**
     * Request scope
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function scope(Request $request)
    {
        return EloquentVariant::collection(
            $this->variants->scope($request)
                ->isOwner()
                ->with(['warehouse', 'product'])
                ->latest()->smartPaginate()
        );
    }

    /**
     * Create variant
     *
     * @param StoreVariant $request
     * @return array
     * @throws \Exception
     */
    public function store(StoreVariant $request)
    {
          $this->variants->create($request->all());
        return notify(_p('storage::notifies.user.variant.success_added_variant', 'Success added variant.'));
    }

    /**
     * Update variant
     *
     * @param UpdateVariant $request
     * @param $id
     * @return array
     */
    public function update(UpdateVariant $request, $id)
    {
        $this->authorize('isOwner', Variant::find($id));
        $this->variants->update($request->all(), $id);
        return notify(_p('storage::notifies.user.variant.success_updated_variant', 'Success updated variant.'));
    }

    /**
     * Delete variant
     *
     * @param $id
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete($id)
    {
        $this->authorize('isOwner', Variant::find($id));
        $this->variants->delete($id);
        return notify(_p('storage::notifies.user.variant.success_deleted_variant', 'Success deleted variant.'));
    }

    /**
     * Select variant ID
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function selectVariantId(Request $request)
    {
        return $this->ajax($this->variants->selectVariantId($request));
    }
}
