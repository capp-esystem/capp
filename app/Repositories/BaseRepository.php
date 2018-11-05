<?php

namespace App\Repositories;

use Yish\Generators\Foundation\Repository\Repository;

class BaseRepository extends Repository
{
    protected $model;

    protected function __construct($model) {
        $this->model = $model;
    }

    public function where($attributes = [], $with = [], $withCount = []) {
        $builder = $this->model;
        collect($attributes)->each(function($value, $column) use (&$builder) {
            if(is_array($value)) {
                $builder = $builder->whereIn($column, $value);
            } else {
                $builder = $builder->where($column, $value);
            }
        });
        return $builder->with($with)->withCount($withCount)->get();
    }

    public function firstOrCreate($condition = [], $attributes = []) {
        return $this->model->firstOrCreate($condition, $attributes);
    }

    public function firstOrNew($condition = [], $attributes = []) {
        return $this->model->firstOrNew($condition, $attributes);
    }

    public function updateOrCreate($condition = [], $attributes = []) {
        return $this->model->updateOrCreate($condition, $attributes);
    }

    public function deleteBy($condition = []) {
        return $this->model->where($condition)->delete();
    }
}
