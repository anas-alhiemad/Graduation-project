<?php

namespace App\Services;

use App\Models\ExamGrade;
use App\Models\CourseSection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;

class ExamGradeService
{
    public function getAllExamGrades(): Collection
    {
        return ExamGrade::with(['student', 'section', 'trainer'])->get();
    }

    public function createExamGrade(array $data): ExamGrade
    {
        $validator = Validator::make($data, ExamGrade::$rules);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        $this->validateSectionMembership($data['section_id'], $data['student_id'], $data['trainer_id']);

        return ExamGrade::create($data);
    }

    public function getExamGrade(int $id): ExamGrade
    {
        return ExamGrade::with(['student', 'section', 'trainer'])->findOrFail($id);
    }

    public function updateExamGrade(int $id, array $data): ExamGrade
    {
        $examGrade = ExamGrade::findOrFail($id);
        
        $validator = Validator::make($data, ExamGrade::$rules);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        $this->validateSectionMembership($data['section_id'], $data['student_id'], $data['trainer_id']);

        $examGrade->update($data);
        return $examGrade;
    }

    public function deleteExamGrade(int $id): bool
    {
        $examGrade = ExamGrade::findOrFail($id);
        return $examGrade->delete();
    }

    public function getStudentGrades(int $studentId): Collection
    {
        return ExamGrade::with(['section', 'trainer'])
            ->where('student_id', $studentId)
            ->get();
    }

    public function getSectionGrades(int $sectionId): Collection
    {
        return ExamGrade::with(['student', 'trainer'])
            ->where('section_id', $sectionId)
            ->get();
    }

    public function getTrainerGrades(int $trainerId): Collection
    {
        return ExamGrade::with(['student', 'section'])
            ->where('trainer_id', $trainerId)
            ->get();
    }

    public function getSectionStatistics(int $sectionId): array
    {
        $grades = ExamGrade::where('section_id', $sectionId)->get();
        
        return [
            'average' => $grades->avg('grade'),
            'highest' => $grades->max('grade'),
            'lowest' => $grades->min('grade'),
            'total_students' => $grades->count(),
            'passing_count' => $grades->where('grade', '>=', 60)->count(),
            'failing_count' => $grades->where('grade', '<', 60)->count(),
        ];
    }

    private function validateSectionMembership(int $sectionId, int $studentId, int $trainerId): void
    {
        $section = CourseSection::findOrFail($sectionId);
        $isStudentInSection = $section->students()->where('student_id', $studentId)->exists();
        $isTrainerInSection = $section->trainers()->where('trainer_id', $trainerId)->exists();

        if (!$isStudentInSection || !$isTrainerInSection) {
            throw new \InvalidArgumentException('Student or trainer is not in the specified section');
        }
    }
} 