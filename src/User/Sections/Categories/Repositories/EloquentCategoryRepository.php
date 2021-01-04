<?php

namespace AwemaPL\Storage\User\Sections\Categories\Repositories;

use AwemaPL\Storage\User\Sections\Categories\Models\Category;
use AwemaPL\Storage\User\Sections\Categories\Repositories\Contracts\CategoryRepository;
use AwemaPL\Storage\User\Sections\Categories\Scopes\EloquentCategoryScopes;
use AwemaPL\Repository\Eloquent\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class EloquentCategoryRepository extends BaseRepository implements CategoryRepository
{
    protected $searchable = [

    ];

    public function entity()
    {
        return Category::class;
    }

    public function scope($request)
    {
        // apply build-in scopes
        parent::scope($request);

        // apply custom scopes
        $this->entity = (new EloquentCategoryScopes($request))->scope($this->entity);
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
       return Category::create($data);
    }

    /**
     * Update category
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
     * Delete category
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
     * Select category ID
     *
     * @param Request $request
     * @return array
     */
    public function selectCategoryId($request){
        /** @var Collection $categories */
        $excludeId = (int)$request->exclude_id;
        $includeId = (int)$request->include_id;
        $categories = $this->scope($request)->isOwner()->where('warehouse_id', $request->warehouse_id)->smartPaginate();
       $data = [];
        foreach ($categories as $category){
            if (!$excludeId || $category->id !== $excludeId){
                if ($category->id === $includeId){
                    $includeId = null;
                }
                array_push($data, [
                    'id' =>$category->getKey(),
                    'name' =>$category->crumbs(),
                ]);
            }
        }
        if ($includeId){
            $category = $this->find($includeId);
            array_unshift($data, [
                'id' =>$category->getKey(),
                'name' =>$category->crumbs(),
            ]);
        }
        return $data;
    }

}
