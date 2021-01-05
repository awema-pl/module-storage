<?php

namespace AwemaPL\Storage\User\Sections\Descriptions\Repositories;

use AwemaPL\Storage\User\Sections\Descriptions\Models\Description;
use AwemaPL\Storage\User\Sections\Descriptions\Repositories\Contracts\DescriptionRepository;
use AwemaPL\Storage\User\Sections\Descriptions\Scopes\EloquentDescriptionScopes;
use AwemaPL\Repository\Eloquent\BaseRepository;
use AwemaPL\Storage\User\Sections\Descriptions\Services\Contracts\Availability;
use AwemaPL\Storage\User\Sections\Descriptions\Services\Contracts\DescriptionType;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class EloquentDescriptionRepository extends BaseRepository implements DescriptionRepository
{
    /** @var DescriptionType $descriptionType */
    protected $descriptionType;

    protected $searchable = [

    ];

    public function __construct(DescriptionType $descriptionType)
    {
        parent::__construct();
        $this->descriptionType = $descriptionType;
    }


    public function entity()
    {
        return Description::class;
    }

    public function scope($request)
    {
        // apply build-in scopes
        parent::scope($request);

        // apply custom scopes
        $this->entity = (new EloquentDescriptionScopes($request))->scope($this->entity);
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
        return Description::create($data);
    }

    /**
     * Update description
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
     * Delete description
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
        $types = $this->descriptionType->getTypes();
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
