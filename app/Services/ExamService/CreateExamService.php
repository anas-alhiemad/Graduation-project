<?php

namespace App\Services\ExamService;

use App\Repositories\ExamRepository;
use App\Repositories\CourseSectionRepository;
use App\Models\Trainer;
use Illuminate\Support\Facades\Auth;

class CreateExamService
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

    public function handle($request)
    {
        $user = Auth::user();

        if (!($user instanceof Trainer)) {
            throw new \Exception('Only trainers can create exams');
        }

        $section = $this->courseSectionRepository->getById($request->course_section_id);

        if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
            throw new \Exception('You are not authorized to create exams for this section');
        }

        $exam = $this->examRepository->create([
            'name' => $request->name,
            'exam_date' => $request->exam_date,
            'course_section_id' => $request->course_section_id,
        ]);

        return response()->json([
            'message' => 'Exam created successfully',
            'exam' => $exam
        ]);
    }
}
