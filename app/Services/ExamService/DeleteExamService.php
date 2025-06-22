<?php

namespace App\Services\ExamService;

use App\Repositories\ExamRepository;
use App\Repositories\CourseSectionRepository;
use App\Models\Trainer;
use Illuminate\Support\Facades\Auth;

class DeleteExamService
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

    public function handle($examId)
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

        if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
            throw new \Exception('You are not authorized to delete exams for this section');
        }

        $this->examRepository->delete($examId);

        return response()->json([
            'message' => 'Exam deleted successfully'
        ]);
    }
}
