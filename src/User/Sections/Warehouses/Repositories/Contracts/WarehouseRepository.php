<?php

namespace AwemaPL\Storage\User\Sections\Warehouses\Repositories\Contracts;

use AwemaPL\Storage\User\Sections\Warehouses\Repositories\EloquentWarehouseRepository;
use AwemaPL\Storage\Sections\Options\Http\Requests\UpdateOption;
use Illuminate\Http\Request;

interface WarehouseRepository
{
    /**
     * Create warehouse
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Scope warehouse
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scope($request);
    
    /**
     * Update warehouse
     *
     * @param array $data
     * @param int $id
     *
     * @return int
     */
    public function update(array $data, $id);
    
    /**
     * Delete warehouse
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
     * Select warehouse ID
     *
     * @param Request $request
     * @return array
     */
    public function selectWarehouseId($request);
}
