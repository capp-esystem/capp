<?php

namespace App\Services;

use Yish\Generators\Foundation\Service\Service;

class BaseService extends Service
{
    protected $repository;

    protected function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function where($attributes, $with = [], $withCount = [])
    {
        return $this->repository->where($attributes, $with, $withCount);
    }
}
