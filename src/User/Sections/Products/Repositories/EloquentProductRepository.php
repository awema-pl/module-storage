<?php

namespace AwemaPL\Storage\User\Sections\Products\Repositories;

use AwemaPL\Storage\User\Sections\Products\Models\Product;
use AwemaPL\Storage\User\Sections\Products\Repositories\Contracts\ProductRepository;
use AwemaPL\Storage\User\Sections\Products\Scopes\EloquentProductScopes;
use AwemaPL\Repository\Eloquent\BaseRepository;
use AwemaPL\Storage\User\Sections\Products\Services\Contracts\Availability;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class EloquentProductRepository extends BaseRepository implements ProductRepository
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
        return Product::class;
    }

    public function scope($request)
    {
        // apply build-in scopes
        parent::scope($request);

        // apply custom scopes
        $this->entity = (new EloquentProductScopes($request))->scope($this->entity);
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
       $product =  Product::create($data);
       $product->categories()->attach($data['default_category_id'], [
           'user_id' =>$data['user_id'],
           'warehouse_id' =>$data['warehouse_id'],
       ]);
       return $product;
    }

    /**
     * Update product
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
        $product = $this->find($id);
        $defaultCategoryId = (int) $data['default_category_id'] ?? null;
        if ($defaultCategoryId && $product->default_category_id !== $defaultCategoryId && !$product->categories->contains($defaultCategoryId) ){
            $product->categories()->attach($data['default_category_id'], [
                'user_id' =>$data['user_id'] ?? Auth::id(),
                'warehouse_id' =>$product->warehouse_id,
            ]);
        }
        return parent::update($data, $id, $attribute);
    }

    /**
     * Delete product
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
     * Select availability
     *
     * @return array
     */
    public function selectAvailability(){
        $availabilities = $this->availability->getAvailabilities();
        $data = [];
        foreach ($availabilities as $availability){
            array_push($data, [
                'id' =>$availability['availability'],
                'name' =>$availability['name'],
            ]);
        }
        return $data;
    }

    /**
     * Select product ID
     *
     * @param Request $request
     * @return array
     */
    public function selectProductId($request){
        /** @var Collection $manufacturers */
        $excludeId = (int)$request->exclude_id;
        $includeId = (int)$request->include_id;
        $products = $this->scope($request)->isOwner()->where('warehouse_id', $request->warehouse_id)->smartPaginate();
        $data = [];
        foreach ($products as $product){
            if (!$excludeId || $product->id !== $excludeId){
                if ($product->id === $includeId){
                    $includeId = null;
                }
                array_push($data, [
                    'id' =>$product->getKey(),
                    'name' =>$product->name,
                ]);
            }
        }
        if ($includeId){
            $product = $this->find($includeId);
            array_unshift($data, [
                'id' =>$product->getKey(),
                'name' =>$product->name,
            ]);
        }
        return $data;
    }
}
