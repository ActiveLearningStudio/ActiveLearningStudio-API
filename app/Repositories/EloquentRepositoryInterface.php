<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface EloquentRepositoryInterface
{
    /**
     * @return Collection
     */
    public function all();

    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes);

    /**
     * @param array $attributes
     * @param $id
     * @return Model
     */
    public function update(array $attributes, $id);

    /**
     * @param $id
     * @return Model
     */
    public function find($id);

    /**
     * @param $id
     * @return int
     */
    public function delete($id);
}
