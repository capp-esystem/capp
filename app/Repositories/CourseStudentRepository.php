<?php

namespace App\Repositories;

use App\CourseUser;

class CourseStudentRepository extends BaseRepository
{
    public function __construct(CourseUser $model)
    {
        parent::__construct($model);
    }
}
