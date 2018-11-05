<?php

namespace App\Services;

use App\Repositories\CriteriaRepository;

class CriteriaService extends BaseService
{
    public function __construct(CriteriaRepository $repository)
    {
        parent::__construct($repository);
    }
}
