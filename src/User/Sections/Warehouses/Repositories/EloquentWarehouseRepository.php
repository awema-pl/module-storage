<?php

namespace AwemaPL\Storage\User\Sections\Warehouses\Repositories;

use AwemaPL\Storage\User\Sections\Warehouses\Models\Warehouse;
use AwemaPL\Storage\User\Sections\Warehouses\Repositories\Contracts\WarehouseRepository;
use AwemaPL\Storage\User\Sections\Warehouses\Scopes\EloquentWarehouseScopes;
use AwemaPL\Repository\Eloquent\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EloquentWarehouseRepository extends BaseRepository implements WarehouseRepository
{

    protected $searchable = [

    ];

    public function entity()
    {
        return Warehouse::class;
    }

    public function scope($request)
    {
        // apply build-in scopes
        parent::scope($request);

        // apply custom scopes
        $this->entity = (new EloquentWarehouseScopes($request))->scope($this->entity);
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
       return Warehouse::create($data);
    }

    /**
     * Update warehouse
     *
     * @param array $data
     * @param int $id
     * @param string $attribute
     *
     * @return int
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        return parent::update($data, $id, $attribute);
    }

    /**
     * Delete warehouse
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
     * Select warehouse ID
     *
     * @param Request $request
     * @return array
     */
    public function selectWarehouseId($request){
        $warehouses = $this->scope($request)->isOwner()->latest()->smartPaginate();
        $data = [];
        foreach ($warehouses as $warehouse){
            array_push($data, [
                'id' =>$warehouse->getKey(),
                'name' =>$warehouse->name,
            ]);
        }
        return $data;
    }
}
