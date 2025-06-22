<?php
namespace App\Services\GradeServices;

use App\Repositories\GradeRepository;
use App\Repositories\ExamRepository;
use App\Repositories\CourseSectionRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\Trainer;

class CreateGradeService
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

   public function create(array $data)
{
    $user = Auth::user();

    if (!($user instanceof Trainer)) {
        throw new \Exception('Only trainers can create grades');
    }

    $exam = $this->examRepository->getById($data['exam_id']);
    if (!$exam) {
        throw new \Exception('Exam not found');
    }

    $section = $this->courseSectionRepository->getById($exam->course_section_id);

    if (!$section->trainers()->where('trainers.id', $user->id)->exists()) {
        throw new \Exception('Not authorized to create grades for this exam');
    }

    if (!$section->students()->where('students.id', $data['student_id'])->exists()) {
        throw new \Exception('Student is not enrolled in this course section');
    }

    $existingGrade = $this->gradeRepository->getByStudentAndExam($data['student_id'], $data['exam_id']);
    if ($existingGrade) {
        throw new \Exception('Grade already exists for this student and exam');
    }

   
    $data['trainer_id'] = $user->id;

    $grade = $this->gradeRepository->create($data);

    return response()->json([
        'message' => 'Grade created successfully',
        'grade' => $grade,
    ]);
}

}
