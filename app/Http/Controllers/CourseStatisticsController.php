<?php

namespace App\Http\Controllers;

use App\Services\CourseService\CourseStatisticsService;

class CourseStatisticsController extends Controller
{
    protected $courseStatistics;

    public function __construct(CourseStatisticsService $courseStatistics)
    {
        $this->courseStatistics = $courseStatistics;
    }
public function topCourses()
{
    $topCourses = $this->courseStatistics->getTopCourses();
    return response()->json([
        'message' => 'Top courses by student registrations',
        'data' => $topCourses
    ]);
}


}
