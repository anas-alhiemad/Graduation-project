<?php

namespace App\Services\SavedCourseService;

use App\Models\Course;
use App\Repositories\SavedCourseRepository;
use Illuminate\Support\Facades\Auth;

class UnsaveCourseService
{
    protected $savedCourseRepository;

    public function __construct(SavedCourseRepository $savedCourseRepository)
    {
        $this->savedCourseRepository = $savedCourseRepository;
    }

    public function handle($courseId)
    {
        $user = Auth::user();

        if (!($user instanceof \App\Models\Student)) {
            throw new \Exception('Only students can unsave courses');
        }

        $course = Course::find($courseId);
        if (!$course) {
            throw new \Exception('Course not found');
        }

        if (!$this->savedCourseRepository->isCourseSaved($user->id, $courseId)) {
            throw new \Exception('Course is not saved');
        }

        $this->savedCourseRepository->unsaveCourse($user->id, $courseId);

        return response()->json([
            'message' => 'Course unsaved successfully'
        ]);
    }
}
