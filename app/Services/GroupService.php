<?php

namespace App\Services;
use League\Csv\Reader;
use App\Repositories\GroupRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GroupService extends BaseService
{
    public function __construct(GroupRepository $repository)
    {
        parent::__construct($repository);
    }

    public function firstOrCreate($attributes)
    {
        return $this->repository->firstOrCreate($attributes, $attributes);
    }

    public function importFromCSV($courseId, UploadedFile $file)
    {
        if (!ini_get("auto_detect_line_endings")) {
            ini_set("auto_detect_line_endings", '1');
        }
        $reader = Reader::createFromFileObject($file->openFile());
        $result = iterator_to_array($reader->fetchAssoc(['sid', 'name', 'email', 'group_name']));
        $result = collect($result);
        $result->shift();
        return $result->pluck('group_name')->unique()->map(function($name) use ($courseId) {
            return $this->firstOrCreate([
                'course_id' => $courseId,
                'name' => trim($name)
            ]);
        });
    }
}
