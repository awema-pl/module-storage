<?php

namespace AwemaPL\Storage\User\Sections\Manufacturers\Repositories;

use AwemaPL\Storage\User\Sections\Manufacturers\Models\Manufacturer;
use AwemaPL\Storage\User\Sections\Manufacturers\Repositories\Contracts\ManufacturerRepository;
use AwemaPL\Storage\User\Sections\Manufacturers\Scopes\EloquentManufacturerScopes;
use AwemaPL\Repository\Eloquent\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class EloquentManufacturerRepository extends BaseRepository implements ManufacturerRepository
{
    protected $searchable = [

    ];

    public function entity()
    {
        return Manufacturer::class;
    }

    public function scope($request)
    {
        // apply build-in scopes
        parent::scope($request);

        // apply custom scopes
        $this->entity = (new EloquentManufacturerScopes($request))->scope($this->entity);
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
       return Manufacturer::create($data);
    }

    /**
     * Update manufacturer
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
     * Delete manufacturer
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
     * Select manufacturer ID
     *
     * @param Request $request
     * @return array
     */
    public function selectManufacturerId($request){
        /** @var Collection $manufacturers */
        $excludeId = (int)$request->exclude_id;
        $includeId = (int)$request->include_id;
        $manufacturers = $this->scope($request)->isOwner()->where('warehouse_id', $request->warehouse_id)->smartPaginate();
       $data = [];
        foreach ($manufacturers as $manufacturer){
            if (!$excludeId || $manufacturer->id !== $excludeId){
                if ($manufacturer->id === $includeId){
                    $includeId = null;
                }
                array_push($data, [
                    'id' =>$manufacturer->getKey(),
                    'name' =>$manufacturer->name,
                ]);
            }
        }
        if ($includeId){
            $manufacturer = $this->find($includeId);
            array_unshift($data, [
                'id' =>$manufacturer->getKey(),
                'name' =>$manufacturer->name,
            ]);
        }
        return $data;
    }

}
