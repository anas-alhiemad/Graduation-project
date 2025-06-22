<?php
namespace App\Services\GradeServices;

use App\Repositories\GradeRepository;
use App\Repositories\ExamRepository;
use App\Repositories\CourseSectionRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\Trainer;

class DisplayGradeService
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

    public function getGradesByExam($examId, $perPage = 10)
    {
        $user = Auth::user();
        $exam = $this->examRepository->getById($examId);
        if (!$exam) {
            throw new \Exception('Exam not found');
        }
        $section = $this->courseSectionRepository->getById($exam->course_section_id);

        if ($user instanceof Trainer && !$section->trainers()->where('trainers.id', $user->id)->exists()) {
            throw new \Exception('Not authorized to view grades for this exam');
        }

        $grades = $this->gradeRepository->getByExam($examId, $perPage);

        return response()->json([
            'message' => 'Grades retrieved successfully',
            'grades' => $grades,
        ]);
    }

    public function getGradesByStudent($studentId, $perPage = 10)
    {
        $user = Auth::user();

        if ($user instanceof Trainer) {
            $studentGrades = $this->gradeRepository->getByStudent($studentId);
            $authorizedGrades = $studentGrades->filter(function ($grade) use ($user) {
                $section = $this->courseSectionRepository->getById($grade->exam->course_section_id);
                return $section->trainers()->where('trainers.id', $user->id)->exists();
            });

            $page = request()->input('page', 1);
            $perPage = request()->input('per_page', 10);
            $paginatedGrades = new \Illuminate\Pagination\LengthAwarePaginator(
                $authorizedGrades->forPage($page, $perPage),
                $authorizedGrades->count(),
                $perPage,
                $page
            );

            return response()->json([
                'message' => 'Grades retrieved successfully',
                'grades' => $paginatedGrades,
            ]);
        }

        if ($user->id === $studentId) {
            $grades = $this->gradeRepository->getByStudent($studentId, $perPage);
            return response()->json([
                'message' => 'Grades retrieved successfully',
                'grades' => $grades,
            ]);
        }

        throw new \Exception('Not authorized to view these grades');
    }
}
