<?php

namespace App\Repositories;

use App\Models\Grade;

class GradeRepository
{
    public function create(array $data): Grade
    {
        return Grade::create($data);
    }

    public function getById($id): ?Grade
    {
        return Grade::find($id);
    }

    public function getByExam($examId, $perPage = 10)
    {
        return Grade::where('exam_id', $examId)
            ->with(['student', 'trainer'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getByStudent($studentId, $perPage = 10)
    {
        return Grade::where('student_id', $studentId)
            ->with(['exam', 'trainer'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getByStudentAndExam($studentId, $examId): ?Grade
    {
        return Grade::where('student_id', $studentId)
            ->where('exam_id', $examId)
            ->first();
    }

    public function update($id, array $data): bool
    {
        return Grade::where('id', $id)->update($data);
    }

    public function delete($id): bool
    {
        return Grade::destroy($id);
    }
} 