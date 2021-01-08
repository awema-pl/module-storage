<?php

namespace AwemaPL\Storage\User\Sections\Products\Repositories\Contracts;

use AwemaPL\Storage\User\Sections\Products\Repositories\EloquentProductRepository;
use AwemaPL\Storage\Sections\Options\Http\Requests\UpdateOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface ProductRepository
{
    /**
     * Create product
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Scope product
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scope($request);
    
    /**
     * Update product
     *
     * @param array $data
     * @param int $id
     *
     * @return int
     */
    public function update(array $data, $id);
    
    /**
     * Delete product
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
     * Add basic where clauses and execute single the query.
     *
     * @param array $conditions
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function firstWhere(array $conditions, array $columns = ['*']);

    /**
     * Select availability
     *
     * @return array
     */
    public function selectAvailability();

    /**
     * Select product ID
     *
     * @param Request $request
     * @return array
     */
    public function selectProductId($request);

    /**
     * Exists by external ID
     *
     * @param int $warehouseId
     * @param string $externalId
     * @param int|null $sourceId
     * @return bool
     */
    public function existsByExternalId(int $warehouseId, string $externalId, int $sourceId = null):bool;
}
