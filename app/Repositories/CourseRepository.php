<?php

namespace App\Repositories;

use App\Course;

class CourseRepository extends BaseRepository
{
    public function __construct(Course $model)
    {
        parent::__construct($model);
    }
}
