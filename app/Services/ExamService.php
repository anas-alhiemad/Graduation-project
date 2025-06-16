<?php

namespace App\Services;

use App\Models\Trainer;
use App\Models\CourseSection;
use App\Repositories\ExamRepository;
use App\Repositories\CourseSectionRepository;
use Illuminate\Support\Facades\Auth;

class ExamService
{
    protected $examRepository;
    protected $courseSectionRepository;

    public function __construct(
        ExamRepository $examRepository,
        CourseSectionRepository $courseSectionRepository
    ) {
        $this->examRepository = $examRepository;
        $this->courseSectionRepository = $courseSectionRepository;
    }

    public function createExam($request)
    {
        $user = Auth::user();
        
        if (!($user instanceof Trainer)) {
            throw new \Exception('Only trainers can create exams');
        }

        $section = $this->courseSectionRepository->getById($request->course_section_id);

        // Verify trainer is assigned to this section
        if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
            throw new \Exception('You are not authorized to create exams for this section');
        }

        $exam = $this->examRepository->create([
            'name' => $request->name,
            'exam_date' => $request->exam_date,
            'course_section_id' => $request->course_section_id
        ]);

        return response()->json([
            'message' => 'Exam created successfully',
            'exam' => $exam
        ]);
    }

    public function getExamsBySection($sectionId, $request)
    {
        $user = Auth::user();
        $section = $this->courseSectionRepository->getById($sectionId);

        // If user is a trainer, verify they are assigned to this section
        if ($user instanceof Trainer) {
            if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
                throw new \Exception('You are not authorized to view exams for this section');
            }
        }

        $perPage = $request->input('per_page', 10);
        $exams = $this->examRepository->getBySection($sectionId, $perPage);
        
        return response()->json([
            'message' => 'Exams retrieved successfully',
            'exams' => $exams
        ]);
    }

    public function updateExam($request, $examId)
    {
        $user = Auth::user();
        
        if (!($user instanceof Trainer)) {
            throw new \Exception('Only trainers can update exams');
        }

        $exam = $this->examRepository->getById($examId);
        if (!$exam) {
            throw new \Exception('Exam not found');
        }

        $section = $this->courseSectionRepository->getById($exam->course_section_id);

        // Verify trainer is assigned to this section
        if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
            throw new \Exception('You are not authorized to update exams for this section');
        }

        $updated = $this->examRepository->update($examId, [
            'name' => $request->name,
            'exam_date' => $request->exam_date
        ]);

        return response()->json([
            'message' => 'Exam updated successfully',
            'exam' => $this->examRepository->getById($examId)
        ]);
    }

    public function deleteExam($examId)
    {
        $user = Auth::user();
        
        if (!($user instanceof Trainer)) {
            throw new \Exception('Only trainers can delete exams');
        }

        $exam = $this->examRepository->getById($examId);
        if (!$exam) {
            throw new \Exception('Exam not found');
        }

        $section = $this->courseSectionRepository->getById($exam->course_section_id);

        // Verify trainer is assigned to this section
        if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
            throw new \Exception('You are not authorized to delete exams for this section');
        }

        $this->examRepository->delete($examId);

        return response()->json([
            'message' => 'Exam deleted successfully'
        ]);
    }
} 