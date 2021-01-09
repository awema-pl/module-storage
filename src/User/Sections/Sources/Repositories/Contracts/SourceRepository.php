<?php

namespace AwemaPL\Storage\User\Sections\Sources\Repositories\Contracts;

use AwemaPL\Storage\User\Sections\Sources\Models\Source;
use Illuminate\Http\Request;
use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Collection;

interface SourceRepository
{
    /**
     * Create source
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Scope source
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scope($request);
    
    /**
     * Update source
     *
     * @param array $data
     * @param int $id
     *
     * @return int
     */
    public function update(array $data, $id);
    
    /**
     * Delete source
     *
     * @param int $id
     */
    public function delete($id);

    /**
     * Find a model by its primary key.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return Model|Collection|static[]|static|null
     */
    public function find($id, $columns = ['*']);

    /**
     * Select source ID
     *
     * @param Request $request
     * @return array
     */
    public function selectSourceId($request);

    /**
     * Select sourceable type
     *
     * @return array
     */
    public function selectSourceableType();

    /**
     * Select sourceable ID
     *
     * @param string $sourceType
     * @return array
     */
    public function selectSourceableId($sourceType);
}
