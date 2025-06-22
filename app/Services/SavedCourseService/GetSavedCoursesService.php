<?php

namespace App\Services\SavedCourseService;

use App\Repositories\SavedCourseRepository;
use Illuminate\Support\Facades\Auth;

class GetSavedCoursesService
{
    protected $savedCourseRepository;

    public function __construct(SavedCourseRepository $savedCourseRepository)
    {
        $this->savedCourseRepository = $savedCourseRepository;
    }

    public function handle($perPage = 10)
    {
        $user = Auth::user();

        if (!($user instanceof \App\Models\Student)) {
            throw new \Exception('Only students can view saved courses');
        }

        $savedCourses = $this->savedCourseRepository->getStudentSavedCourses($user->id, $perPage);

        return response()->json([
            'message' => 'Saved courses retrieved successfully',
            'saved_courses' => $savedCourses
        ]);
    }
}
