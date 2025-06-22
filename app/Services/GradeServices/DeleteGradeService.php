<?php
namespace App\Services\GradeServices;

use App\Repositories\GradeRepository;
use App\Repositories\ExamRepository;
use App\Repositories\CourseSectionRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\Trainer;

class DeleteGradeService
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

    public function delete($gradeId)
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

        if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
            throw new \Exception('Not authorized to delete grades for this exam');
        }

        $this->gradeRepository->delete($gradeId);

        return response()->json([
            'message' => 'Grade deleted successfully',
        ]);
    }
}
