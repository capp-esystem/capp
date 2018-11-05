<?php

namespace App\Repositories;

use Yish\Generators\Foundation\Repository\Repository;
use App\User;

class StudentRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
