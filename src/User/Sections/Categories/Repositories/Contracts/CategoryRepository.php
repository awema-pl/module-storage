<?php

namespace AwemaPL\Storage\User\Sections\Categories\Repositories\Contracts;

use AwemaPL\Storage\User\Sections\Categories\Repositories\EloquentCategoryRepository;
use AwemaPL\Storage\Sections\Options\Http\Requests\UpdateOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface CategoryRepository
{
    /**
     * Create category
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Scope category
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scope($request);
    
    /**
     * Update category
     *
     * @param array $data
     * @param int $id
     *
     * @return int
     */
    public function update(array $data, $id);
    
    /**
     * Delete category
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
     * First or update a record matching the attributes, and fill it with values.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function firstOrCreate(array $attributes, array $values);

    /**
     * Select category ID
     *
     * @param Request $request
     * @return array
     */
    public function selectCategoryId($request);
}
