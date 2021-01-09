<?php

namespace AwemaPL\Storage\User\Sections\Sources\Http\Controllers;

use AwemaPL\Storage\User\Sections\Sources\Http\Requests\BindProductSource;
use AwemaPL\Storage\User\Sections\Sources\Http\Requests\ImportProductSource;
use AwemaPL\Storage\User\Sections\Sources\Http\Requests\StoreSource;
use AwemaPL\Storage\User\Sections\Sources\Http\Requests\UpdateProductSource;
use AwemaPL\Storage\User\Sections\Sources\Http\Requests\UpdateSource;
use AwemaPL\Storage\User\Sections\Sources\Jobs\ImportProductJob;
use AwemaPL\Storage\User\Sections\Sources\Jobs\UpdateProductJob;
use AwemaPL\Storage\User\Sections\Sources\Models\Source;
use AwemaPL\Storage\User\Sections\Sources\Repositories\Contracts\SourceRepository;
use AwemaPL\Storage\User\Sections\Sources\Resources\EloquentSource;
use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use AwemaPL\Storage\Client\ProductIntegratorClient;

class SourceController extends Controller
{

    use RedirectsTo, AuthorizesRequests;

    /**
     * Sources repository instance
     *
     * @var SourceRepository
     */
    protected $sources;

    public function __construct(SourceRepository $sources)
    {
        $this->sources = $sources;
    }

    /**
     * Display sources
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('storage::user.sections.sources.index');
    }

    /**
     * Request scope
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function scope(Request $request)
    {
        return EloquentSource::collection(
            $this->sources->scope($request)
                ->isOwner()
                ->with(['warehouse', 'sourceable'])
                ->latest()->smartPaginate()
        );
    }

    /**
     * Add source
     *
     * @param StoreSource $request
     * @return array
     * @throws \Exception
     */
    public function store(StoreSource $request)
    {
        $this->sources->create($request->all());
        return notify(_p('storage::notifies.user.source.success_added_source', 'Success added source.'));
    }

    /**
     * Update source
     *
     * @param UpdateSource $request
     * @param $id
     * @return array
     */
    public function update(UpdateSource $request, $id)
    {
        $this->authorize('isOwner', Source::find($id));
        $this->sources->update($request->all(), $id);
        return notify(_p('storage::notifies.user.source.success_updated_source', 'Success updated source.'));
    }
    
    /**
     * Destroy source
     *
     * @param $id
     * @return array
     */
    public function destroy($id)
    {
        $this->authorize('isOwner', Source::find($id));
        $this->sources->delete($id);
        return notify(_p('storage::notifies.user.source.success_deleted_source', 'Success deleted source.'));
    }

    /**
     * Select source ID
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function selectSourceId(Request $request)
    {
        return $this->ajax($this->sources->selectSourceId($request));
    }

    /**
     * Select source type
     *
     * @return JsonResponse
     */
    public function selectSourceableType()
    {
        return $this->ajax($this->sources->selectSourceableType());
    }

    /**
     * Select sourceable ID
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function selectSourceableId(Request $request)
    {
        return $this->ajax($this->sources->selectSourceableId($request->sourceable_type));
    }

    /**
     * Import products
     *
     * @param ImportProductSource $request
     * @param $id
     */
    public function importProducts(ImportProductSource $request, $id)
    {
        $source = Source::find($id);
        dispatch(new ImportProductJob($source, $request->all()));
        return notify(_p('storage::notifies.user.source.successfully_started_importing_products', 'You successfully started importing products.'));
    }

    /**
     * Update products
     *
     * @param UpdateProductSource $request
     * @param $id
     */
    public function updateProducts(UpdateProductSource $request, $id)
    {
        $source = Source::find($id);
        $options = $request->all();
        $options['generate_duplicate_product']= true;
        dispatch(new UpdateProductJob($source, $options));
        return notify(_p('storage::notifies.user.source.successfully_started_updating_products', 'You successfully started updating products.'));
    }
}
