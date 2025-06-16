<?php

namespace App\Http\Controllers;

use App\Services\SavedCourseService;
use Illuminate\Http\Request;

class SavedCourseController extends Controller
{
    protected $savedCourseService;

    public function __construct(SavedCourseService $savedCourseService)
    {
        $this->savedCourseService = $savedCourseService;
    }

    public function saveCourse($courseId)
    {
        return $this->savedCourseService->saveCourse($courseId);
    }

    public function unsaveCourse($courseId)
    {
        return $this->savedCourseService->unsaveCourse($courseId);
    }

    public function getMySavedCourses(Request $request)
    {
        return $this->savedCourseService->getMySavedCourses($request);
    }

    public function getSavedCoursesCount()
    {
        return $this->savedCourseService->getSavedCoursesCount();
    }
} 