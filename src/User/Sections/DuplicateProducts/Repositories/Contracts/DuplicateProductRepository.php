<?php

namespace AwemaPL\Storage\User\Sections\DuplicateProducts\Repositories\Contracts;

use AwemaPL\Storage\User\Sections\DuplicateProducts\Repositories\EloquentDuplicateProductRepository;
use AwemaPL\Storage\Sections\Options\Http\Requests\UpdateOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface DuplicateProductRepository
{
    /**
     * Create duplicate product
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Scope duplicate product
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scope($request);
    
    /**
     * Update duplicate product
     *
     * @param array $data
     * @param int $id
     *
     * @return int
     */
    public function update(array $data, $id);
    
    /**
     * Delete duplicate product
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
     * Add basic where clauses and execute the query.
     *
     * @param array $conditions
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findWhere(array $conditions, array $columns = ['*']);

    /**
     * First or update a record matching the attributes, and fill it with values.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function firstOrCreate(array $attributes, array $values);

    /**
     * Add basic where clauses and execute the query.
     *
     * @param array $conditions
     */
    public function deleteWhere(array $conditions);
}
