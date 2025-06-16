<?php

namespace App\Services;

use App\Models\Trainer;
use App\Models\Exam;
use App\Repositories\GradeRepository;
use App\Repositories\ExamRepository;
use App\Repositories\CourseSectionRepository;
use Illuminate\Support\Facades\Auth;

class GradeService
{
    protected $gradeRepository;
    protected $examRepository;
    protected $courseSectionRepository;

    public function __construct(
        GradeRepository $gradeRepository,
        ExamRepository $examRepository,
        CourseSectionRepository $courseSectionRepository
    ) {
        $this->gradeRepository = $gradeRepository;
        $this->examRepository = $examRepository;
        $this->courseSectionRepository = $courseSectionRepository;
    }

    public function createGrade($request)
    {
        $user = Auth::user();
        
        if (!($user instanceof Trainer)) {
            throw new \Exception('Only trainers can create grades');
        }

        $exam = $this->examRepository->getById($request->exam_id);
        if (!$exam) {
            throw new \Exception('Exam not found');
        }

        $section = $this->courseSectionRepository->getById($exam->course_section_id);

        // Verify trainer is assigned to this section
        if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
            throw new \Exception('You are not authorized to create grades for this exam');
        }

        // Verify student is enrolled in this section
        if (!$section->students()->where('students.id', $request->student_id)->exists()) {
            throw new \Exception('Student is not enrolled in this course section');
        }

        // Check if grade already exists
        $existingGrade = $this->gradeRepository->getByStudentAndExam($request->student_id, $request->exam_id);
        if ($existingGrade) {
            throw new \Exception('Grade already exists for this student and exam');
        }

        $grade = $this->gradeRepository->create([
            'student_id' => $request->student_id,
            'exam_id' => $request->exam_id,
            'trainer_id' => $user->id,
            'grade' => $request->grade
        ]);

        return response()->json([
            'message' => 'Grade created successfully',
            'grade' => $grade
        ]);
    }

    public function getGradesByExam($examId, $request)
    {
        $user = Auth::user();
        $exam = $this->examRepository->getById($examId);
        
        if (!$exam) {
            throw new \Exception('Exam not found');
        }

        $section = $this->courseSectionRepository->getById($exam->course_section_id);

        // If user is a trainer, verify they are assigned to this section
        if ($user instanceof Trainer) {
            if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
                throw new \Exception('You are not authorized to view grades for this exam');
            }
        }

        $perPage = $request->input('per_page', 10);
        $grades = $this->gradeRepository->getByExam($examId, $perPage);
        
        return response()->json([
            'message' => 'Grades retrieved successfully',
            'grades' => $grades
        ]);
    }

    public function getGradesByStudent($studentId, $request)
    {
        $user = Auth::user();
        
        // If user is a trainer, verify they are assigned to the student's sections
        if ($user instanceof Trainer) {
            $studentGrades = $this->gradeRepository->getByStudent($studentId);
            $authorizedGrades = $studentGrades->filter(function ($grade) use ($user) {
                $section = $this->courseSectionRepository->getById($grade->exam->course_section_id);
                return $section->trainers()->where('trainers.id', $user->id)->exists();
            });

            $perPage = $request->input('per_page', 10);
            $paginatedGrades = new \Illuminate\Pagination\LengthAwarePaginator(
                $authorizedGrades->forPage($request->input('page', 1), $perPage),
                $authorizedGrades->count(),
                $perPage,
                $request->input('page', 1)
            );

            return response()->json([
                'message' => 'Grades retrieved successfully',
                'grades' => $paginatedGrades
            ]);
        }

        // If user is the student, they can see their own grades
        if ($user->id === $studentId) {
            $perPage = $request->input('per_page', 10);
            $grades = $this->gradeRepository->getByStudent($studentId, $perPage);
            return response()->json([
                'message' => 'Grades retrieved successfully',
                'grades' => $grades
            ]);
        }

        throw new \Exception('You are not authorized to view these grades');
    }

    public function updateGrade($request, $gradeId)
    {
        $user = Auth::user();
        
        if (!($user instanceof Trainer)) {
            throw new \Exception('Only trainers can update grades');
        }

        $grade = $this->gradeRepository->getById($gradeId);
        if (!$grade) {
            throw new \Exception('Grade not found');
        }

        $exam = $this->examRepository->getById($grade->exam_id);
        $section = $this->courseSectionRepository->getById($exam->course_section_id);

        // Verify trainer is assigned to this section
        if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
            throw new \Exception('You are not authorized to update grades for this exam');
        }

        $updated = $this->gradeRepository->update($gradeId, [
            'grade' => $request->grade
        ]);

        return response()->json([
            'message' => 'Grade updated successfully',
            'grade' => $this->gradeRepository->getById($gradeId)
        ]);
    }

    public function deleteGrade($gradeId)
    {
        $user = Auth::user();
        
        if (!($user instanceof Trainer)) {
            throw new \Exception('Only trainers can delete grades');
        }

        $grade = $this->gradeRepository->getById($gradeId);
        if (!$grade) {
            throw new \Exception('Grade not found');
        }

        $exam = $this->examRepository->getById($grade->exam_id);
        $section = $this->courseSectionRepository->getById($exam->course_section_id);

        // Verify trainer is assigned to this section
        if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
            throw new \Exception('You are not authorized to delete grades for this exam');
        }

        $this->gradeRepository->delete($gradeId);

        return response()->json([
            'message' => 'Grade deleted successfully'
        ]);
    }
} 