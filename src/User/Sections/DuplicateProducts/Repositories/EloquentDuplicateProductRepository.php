<?php

namespace AwemaPL\Storage\User\Sections\DuplicateProducts\Repositories;

use AwemaPL\Repository\Criteria\FindWhere;
use AwemaPL\Storage\Common\Exceptions\StorageException;
use AwemaPL\Storage\User\Sections\DuplicateProducts\Models\DuplicateProduct;
use AwemaPL\Storage\User\Sections\DuplicateProducts\Repositories\Contracts\DuplicateProductRepository;
use AwemaPL\Storage\User\Sections\DuplicateProducts\Scopes\EloquentDuplicateProductScopes;
use AwemaPL\Repository\Eloquent\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class EloquentDuplicateProductRepository extends BaseRepository implements DuplicateProductRepository
{
    protected $searchable = [

    ];

    public function entity()
    {
        return DuplicateProduct::class;
    }

    public function scope($request)
    {
        // apply build-in scopes
        parent::scope($request);

        // apply custom scopes
        $this->entity = (new EloquentDuplicateProductScopes($request))->scope($this->entity);
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
       return DuplicateProduct::create($data);
    }

    /**
     * Update duplicate product
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
     * Delete duplicate product
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
     * Add basic where clauses and execute the query.
     *
     * @param array $conditions
     */
    public function deleteWhere(array $conditions){

        if ($conditions){
            $query = DuplicateProduct::query();
            foreach ($conditions as $key => $value){
                $query->where($key, $value);
            }
            $query->delete();
        }
    }
}
