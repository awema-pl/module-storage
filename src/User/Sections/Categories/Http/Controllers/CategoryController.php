<?php

namespace AwemaPL\Storage\User\Sections\Categories\Http\Controllers;

use AwemaPL\Storage\User\Sections\Categories\Models\Category;
use AwemaPL\Storage\Admin\Sections\Settings\Repositories\Contracts\SettingRepository;
use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Storage\User\Sections\Categories\Http\Requests\StoreCategory;
use AwemaPL\Storage\User\Sections\Categories\Http\Requests\UpdateCategory;
use AwemaPL\Storage\User\Sections\Categories\Repositories\Contracts\CategoryRepository;
use AwemaPL\Storage\User\Sections\Categories\Resources\EloquentCategory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CategoryController extends Controller
{
    use RedirectsTo, AuthorizesRequests;

    /** @var CategoryRepository $categories */
    protected $categories;

    /** @var SettingRepository */
    protected $settings;

    public function __construct(CategoryRepository $categories, SettingRepository $settings)
    {
        $this->categories = $categories;
        $this->settings = $settings;
    }

    /**
     * Display categories
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('storage::user.sections.categories.index');
    }

    /**
     * Request scope
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function scope(Request $request)
    {
        return EloquentCategory::collection(
            $this->categories->scope($request)
                ->isOwner()
                ->latest()->smartPaginate()
        );
    }

    /**
     * Create category
     *
     * @param StoreCategory $request
     * @return array
     * @throws \Exception
     */
    public function store(StoreCategory $request)
    {
          $this->categories->create($request->all());
        return notify(_p('storage::notifies.user.category.success_added_category', 'Success added category.'));
    }

    /**
     * Update category
     *
     * @param UpdateCategory $request
     * @param $id
     * @return array
     */
    public function update(UpdateCategory $request, $id)
    {
        $this->authorize('isOwner', Category::find($id));
        $this->categories->update($request->all(), $id);
        return notify(_p('storage::notifies.user.category.success_updated_category', 'Success updated category.'));
    }

    /**
     * Delete category
     *
     * @param $id
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete($id)
    {
        $this->authorize('isOwner', Category::find($id));
        $this->categories->delete($id);
        return notify(_p('storage::notifies.user.category.success_deleted_category', 'Success deleted category.'));
    }

    /**
     * Select category ID
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function selectCategoryId(Request $request)
    {
        return $this->ajax($this->categories->selectCategoryId($request));
    }
}
