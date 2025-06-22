<?php

namespace App\Services\SavedCourseService;

use App\Repositories\SavedCourseRepository;
use Illuminate\Support\Facades\Auth;

class SavedCoursesCountService
{
    protected $savedCourseRepository;

    public function __construct(SavedCourseRepository $savedCourseRepository)
    {
        $this->savedCourseRepository = $savedCourseRepository;
    }

    public function handle()
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
