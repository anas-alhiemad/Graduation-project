<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SavedCourseService\SaveCourseService;
use App\Services\SavedCourseService\UnsaveCourseService;
use App\Services\SavedCourseService\GetSavedCoursesService;
use App\Services\SavedCourseService\SavedCoursesCountService;

class SavedCourseController extends Controller
{
    protected $saveService, $unsaveService, $getService, $countService;

    public function __construct(
        SaveCourseService $saveService,
        UnsaveCourseService $unsaveService,
        GetSavedCoursesService $getService,
        SavedCoursesCountService $countService
    ) {
        $this->saveService = $saveService;
        $this->unsaveService = $unsaveService;
        $this->getService = $getService;
        $this->countService = $countService;
    }

    public function saveCourse($courseId)
    {
        return $this->saveService->handle($courseId);
    }

    public function unsaveCourse($courseId)
    {
        return $this->unsaveService->handle($courseId);
    }

    public function getMySavedCourses(Request $request)
    {
        return $this->getService->handle($request->input('per_page', 10));
    }

    public function getSavedCoursesCount()
    {
        return $this->countService->handle();
    }
}
