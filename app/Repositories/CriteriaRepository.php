<?php

namespace App\Repositories;

use App\Criteria;

class CriteriaRepository extends BaseRepository
{
    public function __construct(Criteria $model)
    {
        parent::__construct($model);
    }
}
