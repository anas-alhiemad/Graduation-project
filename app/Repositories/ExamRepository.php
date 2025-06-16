<?php

namespace App\Repositories;

use App\Models\Exam;

class ExamRepository
{
    public function create(array $data): Exam
    {
        return Exam::create($data);
    }

    public function getById($id): ?Exam
    {
        return Exam::find($id);
    }

    public function getBySection($sectionId, $perPage = 10)
    {
        return Exam::where('course_section_id', $sectionId)
            ->orderBy('exam_date', 'desc')
            ->paginate($perPage);
    }

    public function update($id, array $data): bool
    {
        return Exam::where('id', $id)->update($data);
    }

    public function delete($id): bool
    {
        return Exam::destroy($id);
    }
} 