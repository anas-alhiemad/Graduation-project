<?php

namespace App\Services\CourseSectionServices;

use App\Repositories\CourseSectionRepository;

class SecretaryArchiveService
{
    protected $courseSectionRepository;

    public function __construct(CourseSectionRepository $courseSectionRepository)
    {
        $this->courseSectionRepository = $courseSectionRepository;
    }

    public function getArchivedCoursesForStudent($studentId)
    {
        $courses = $this->courseSectionRepository->getStudentCoursesFinishedById($studentId);
        return response()->json([
            'message' => 'Archived courses for student',
            'courses' => $courses
        ]);
    }

    public function getArchivedCoursesForTrainer($trainerId)
    {
        $courses = $this->courseSectionRepository->getTrainerCoursesFinishedById($trainerId);
        return response()->json([
            'message' => 'Archived courses for trainer',
            'courses' => $courses
        ]);
    }
}
