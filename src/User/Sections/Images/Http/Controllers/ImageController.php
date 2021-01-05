<?php

namespace AwemaPL\Storage\User\Sections\Images\Http\Controllers;

use AwemaPL\Storage\User\Sections\Images\Models\Image;
use AwemaPL\Storage\Admin\Sections\Settings\Repositories\Contracts\SettingRepository;
use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Storage\User\Sections\Images\Http\Requests\StoreImage;
use AwemaPL\Storage\User\Sections\Images\Http\Requests\UpdateImage;
use AwemaPL\Storage\User\Sections\Images\Repositories\Contracts\ImageRepository;
use AwemaPL\Storage\User\Sections\Images\Resources\EloquentImage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ImageController extends Controller
{
    use RedirectsTo, AuthorizesRequests;

    /** @var ImageRepository $images */
    protected $images;

    /** @var SettingRepository */
    protected $settings;

    public function __construct(ImageRepository $images, SettingRepository $settings)
    {
        $this->images = $images;
        $this->settings = $settings;
    }

    /**
     * Display images
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('storage::user.sections.images.index');
    }

    /**
     * Request scope
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function scope(Request $request)
    {
        return EloquentImage::collection(
            $this->images->scope($request)
                ->isOwner()
                ->with(['warehouse', 'product', 'variant'])
                ->latest()->smartPaginate()
        );
    }

    /**
     * Create image
     *
     * @param StoreImage $request
     * @return array
     * @throws \Exception
     */
    public function store(StoreImage $request)
    {
          $this->images->create($request->all());
        return notify(_p('storage::notifies.user.image.success_added_image', 'Success added image.'));
    }

    /**
     * Update image
     *
     * @param UpdateImage $request
     * @param $id
     * @return array
     */
    public function update(UpdateImage $request, $id)
    {
        $this->authorize('isOwner', Image::find($id));
        $this->images->update($request->all(), $id);
        return notify(_p('storage::notifies.user.image.success_updated_image', 'Success updated image.'));
    }

    /**
     * Delete image
     *
     * @param $id
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete($id)
    {
        $this->authorize('isOwner', Image::find($id));
        $this->images->delete($id);
        return notify(_p('storage::notifies.user.image.success_deleted_image', 'Success deleted image.'));
    }
}
