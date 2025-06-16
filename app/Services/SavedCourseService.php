<?php

namespace App\Services;

use App\Repositories\SavedCourseRepository;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class SavedCourseService
{
    protected $savedCourseRepository;

    public function __construct(SavedCourseRepository $savedCourseRepository)
    {
        $this->savedCourseRepository = $savedCourseRepository;
    }

    public function saveCourse($courseId)
    {
        $user = Auth::user();
        
        if (!($user instanceof \App\Models\Student)) {
            throw new \Exception('Only students can save courses');
        }

        // Check if course exists
        $course = Course::find($courseId);
        if (!$course) {
            throw new \Exception('Course not found');
        }

        // Check if already saved
        if ($this->savedCourseRepository->isCourseSaved($user->id, $courseId)) {
            throw new \Exception('Course is already saved');
        }

        $this->savedCourseRepository->saveCourse($user->id, $courseId);

        return response()->json([
            'message' => 'Course saved successfully'
        ]);
    }

    public function unsaveCourse($courseId)
    {
        $user = Auth::user();
        
        if (!($user instanceof \App\Models\Student)) {
            throw new \Exception('Only students can unsave courses');
        }

        // Check if course exists
        $course = Course::find($courseId);
        if (!$course) {
            throw new \Exception('Course not found');
        }

        // Check if course is saved
        if (!$this->savedCourseRepository->isCourseSaved($user->id, $courseId)) {
            throw new \Exception('Course is not saved');
        }

        $this->savedCourseRepository->unsaveCourse($user->id, $courseId);

        return response()->json([
            'message' => 'Course unsaved successfully'
        ]);
    }

    public function getMySavedCourses($request)
    {
        $user = Auth::user();
        
        if (!($user instanceof \App\Models\Student)) {
            throw new \Exception('Only students can view saved courses');
        }

        $savedCourses = $this->savedCourseRepository->getStudentSavedCourses(
            $user->id,
            $request->input('per_page', 10)
        );

        return response()->json([
            'message' => 'Saved courses retrieved successfully',
            'saved_courses' => $savedCourses
        ]);
    }

    public function getSavedCoursesCount()
    {
        $user = Auth::user();
        
        if (!($user instanceof \App\Models\Student)) {
            throw new \Exception('Only students can view saved courses count');
        }

        $count = $this->savedCourseRepository->getSavedCoursesCount($user->id);

        return response()->json([
            'message' => 'Saved courses count retrieved successfully',
            'count' => $count
        ]);
    }
} 