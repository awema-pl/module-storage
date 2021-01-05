<?php

namespace AwemaPL\Storage\User\Sections\Features\Repositories;

use AwemaPL\Storage\User\Sections\Features\Models\Feature;
use AwemaPL\Storage\User\Sections\Features\Repositories\Contracts\FeatureRepository;
use AwemaPL\Storage\User\Sections\Features\Scopes\EloquentFeatureScopes;
use AwemaPL\Repository\Eloquent\BaseRepository;
use AwemaPL\Storage\User\Sections\Features\Services\Contracts\FeatureType;
use Illuminate\Support\Facades\Auth;

class EloquentFeatureRepository extends BaseRepository implements FeatureRepository
{
    /** @var FeatureType $featureType */
    protected $featureType;

    protected $searchable = [

    ];

    public function __construct(FeatureType $featureType)
    {
        parent::__construct();
        $this->featureType = $featureType;
    }


    public function entity()
    {
        return Feature::class;
    }

    public function scope($request)
    {
        // apply build-in scopes
        parent::scope($request);

        // apply custom scopes
        $this->entity = (new EloquentFeatureScopes($request))->scope($this->entity);
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
        return Feature::create($data);
    }

    /**
     * Update feature
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
     * Delete feature
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
     * Select type
     *
     * @return array
     */
    public function selectType(){
        $types = $this->featureType->getTypes();
        $data = [];
        foreach ($types as $type){
            array_push($data, [
                'id' =>$type['type'],
                'name' =>$type['name'],
            ]);
        }
        return $data;
    }
}
