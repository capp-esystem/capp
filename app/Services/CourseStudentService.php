<?php

namespace App\Services;

use League\Csv\Reader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repositories\CourseStudentRepository;
use App\Repositories\StudentRepository;
use App\Repositories\GroupRepository;

class CourseStudentService extends BaseService
{
    public function __construct(CourseStudentRepository $repository, StudentRepository $studentRepository, GroupRepository $groupRepository)
    {
        parent::__construct($repository);
        $this->studentRepository = $studentRepository;
        $this->groupRepository = $groupRepository;
    }

    public function firstOrCreate($attributes)
    {
        return $this->repository->firstOrCreate(['user_id' => $attributes['user_id'], 'course_id' => $attributes['course_id']], $attributes);
    }

    public function updateOrCreate($attributes)
    {
        return $this->repository->updateOrCreate(['user_id' => $attributes['user_id'], 'course_id' => $attributes['course_id']], $attributes);
    }

    public function importFromCSV($courseId, UploadedFile $file)
    {
        if (!ini_get("auto_detect_line_endings")) {
            ini_set("auto_detect_line_endings", '1');
        }
        $result = $this->readFile($file);
        $students = $this->studentRepository->where([
            'email' => $result->pluck('email')->unique()->toArray(),
        ], [])->map(function($student) {
            $student->email = strtolower($student->email);
            return $student;
        })->keyBy('email');
        $groups = $this->groupRepository->where([
            'name' => $result->pluck('group_name')->unique()->toArray(),
            'course_id' => $courseId
        ], [])->map(function($group) {
            $group->name = strtolower($group->name);
            return $group;
        })->keyBy('name');
        return $result->map(function($relation) use ($courseId, $students, $groups) {
            $user = $students->get(trim(strtolower($relation['email'])));
            $group = $groups->get(trim(strtolower($relation['group_name'])));
            if (!is_null($user) && !is_null($group)) {
                return $this->updateOrCreate([
                    'user_id' => $user->id,
                    'group_id' => $group->id,
                    'course_id' => $courseId
                ]);
            }
            return null;
        })->filter();
    }

    private function readFile(UploadedFile $file)
    {
        $reader = Reader::createFromFileObject($file->openFile());
        $result = iterator_to_array($reader->fetchAssoc(['sid', 'name', 'email', 'group_name']));
        $result = collect($result);
        $result->shift();
        return $result;
    }
}
