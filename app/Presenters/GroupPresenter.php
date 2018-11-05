<?php

namespace App\Presenters;

class GroupPresenter
{
    public function consistFormat($group)
    {
        $group->students_count = $group->students_count;
        return $group->getAttributes();
    }
}
