<?php

namespace AwemaPL\Storage\User\Sections\Features\Repositories\Contracts;

use AwemaPL\Storage\User\Sections\Features\Repositories\EloquentFeatureRepository;
use AwemaPL\Storage\Sections\Options\Http\Requests\UpdateOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface FeatureRepository
{
    /**
     * Create feature
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Scope feature
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scope($request);
    
    /**
     * Update feature
     *
     * @param array $data
     * @param int $id
     *
     * @return int
     */
    public function update(array $data, $id);
    
    /**
     * Delete feature
     *
     * @param int $id
     */
    public function delete($id);

    /**
     * Find a model by its primary key.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function find($id, $columns = ['*']);

    /**
     * Select type
     *
     * @return array
     */
    public function selectType();
}
