<?php

namespace AwemaPL\Storage\User\Sections\Images\Repositories;

use AwemaPL\Storage\User\Sections\Images\Models\Image;
use AwemaPL\Storage\User\Sections\Images\Repositories\Contracts\ImageRepository;
use AwemaPL\Storage\User\Sections\Images\Scopes\EloquentImageScopes;
use AwemaPL\Repository\Eloquent\BaseRepository;
use AwemaPL\Storage\User\Sections\Images\Services\Contracts\Availability;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class EloquentImageRepository extends BaseRepository implements ImageRepository
{
    protected $searchable = [

    ];

    public function entity()
    {
        return Image::class;
    }

    public function scope($request)
    {
        // apply build-in scopes
        parent::scope($request);

        // apply custom scopes
        $this->entity = (new EloquentImageScopes($request))->scope($this->entity);
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
        return Image::create($data);
    }

    /**
     * Update image
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
     * Delete image
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
}
