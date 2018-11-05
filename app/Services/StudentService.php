<?php

namespace App\Services;

use Hash;
use League\Csv\Reader;
use App\Repositories\StudentRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class StudentService extends BaseService
{
    public function __construct(StudentRepository $repository)
    {
        parent::__construct($repository);
    }

    public function firstOrCreate($attributes)
    {
        $attributes['password'] = Hash::make($attributes['email']);
        return $this->repository->firstOrCreate(['email' => $attributes['email']], $attributes);
    }

    public function importFromCSV(UploadedFile $file)
    {
        if (!ini_get("auto_detect_line_endings")) {
            ini_set("auto_detect_line_endings", '1');
        }
        $reader = Reader::createFromFileObject($file->openFile());
        $result = iterator_to_array($reader->fetchAssoc(['sid', 'name', 'email']));
        $result = collect($result);
        $result->shift();
        return $result->map(function ($student) {
            $student['name'] = trim($student['name']);
            $student['email'] = trim($student['email']);
            return $this->firstOrCreate($student);
        });
    }

    public function changePassword($userId, $password)
    {
        return $this->repository->update($userId, [
            'password' => Hash::make($password)
        ]);
    }
}
