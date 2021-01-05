<?php

namespace AwemaPL\Storage\User\Sections\Variants\Repositories\Contracts;

use AwemaPL\Storage\User\Sections\Variants\Repositories\EloquentVariantRepository;
use AwemaPL\Storage\Sections\Options\Http\Requests\UpdateOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface VariantRepository
{
    /**
     * Create variant
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Scope variant
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scope($request);
    
    /**
     * Update variant
     *
     * @param array $data
     * @param int $id
     *
     * @return int
     */
    public function update(array $data, $id);
    
    /**
     * Delete variant
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
     * Select variant ID
     *
     * @param Request $request
     * @return array
     */
    public function selectVariantId($request);
}
