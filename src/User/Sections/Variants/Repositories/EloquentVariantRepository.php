<?php

namespace AwemaPL\Storage\User\Sections\Variants\Repositories;

use AwemaPL\Storage\User\Sections\Variants\Models\Variant;
use AwemaPL\Storage\User\Sections\Variants\Repositories\Contracts\VariantRepository;
use AwemaPL\Storage\User\Sections\Variants\Scopes\EloquentVariantScopes;
use AwemaPL\Repository\Eloquent\BaseRepository;
use AwemaPL\Storage\User\Sections\Products\Services\Contracts\Availability;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class EloquentVariantRepository extends BaseRepository implements VariantRepository
{
    /** @var Availability $availability */
    protected $availability;

    protected $searchable = [

    ];

    public function __construct(Availability $availability)
    {
        parent::__construct();
        $this->availability = $availability;
    }


    public function entity()
    {
        return Variant::class;
    }

    public function scope($request)
    {
        // apply build-in scopes
        parent::scope($request);

        // apply custom scopes
        $this->entity = (new EloquentVariantScopes($request))->scope($this->entity);
        return $this;
    }

    /**
     * Create new role
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        $data['user_id'] = $data['user_id'] ?? Auth::id();
       return Variant::create($data);
    }

    /**
     * Update variant
     *
     * @param array $data
     * @param int $id
     * @param string $attribute
     *
     * @return int
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        unset($data['warehouse_id']);
        return parent::update($data, $id, $attribute);
    }

    /**
     * Delete variant
     *
     * @param int $id
     */
    public function delete($id){
        $this->destroy($id);
    }

    /**
     * Find a model by its primary key.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function find($id, $columns = ['*']){
        return parent::find($id, $columns);
    }

    /**
     * Select variant ID
     *
     * @param Request $request
     * @return array
     */
    public function selectVariantId($request){
        /** @var Collection $manufacturers */
        $excludeId = (int)$request->exclude_id;
        $includeId = (int)$request->include_id;
        $query = $this->scope($request)->isOwner()->where('warehouse_id', $request->warehouse_id);
        if ($request->product_id){
            $query->where('product_id', $request->product_id);
        }
        $variants = $query->smartPaginate();
        $data = [];
        foreach ($variants as $variant){
            if (!$excludeId || $variant->id !== $excludeId){
                if ($variant->id === $includeId){
                    $includeId = null;
                }
                array_push($data, [
                    'id' =>$variant->getKey(),
                    'name' =>$variant->name,
                ]);
            }
        }
        if ($includeId){
            $variant = $this->find($includeId);
            array_unshift($data, [
                'id' =>$variant->getKey(),
                'name' =>$variant->name,
            ]);
        }
        return $data;
    }
}
