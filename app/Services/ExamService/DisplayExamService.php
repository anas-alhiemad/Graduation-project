<?php

namespace App\Services\ExamService;

use App\Repositories\ExamRepository;
use App\Repositories\CourseSectionRepository;
use App\Models\Trainer;
use Illuminate\Support\Facades\Auth;

class DisplayExamService
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

    public function handle($sectionId, $request)
    {
        $user = Auth::user();
        $section = $this->courseSectionRepository->getById($sectionId);

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
}
