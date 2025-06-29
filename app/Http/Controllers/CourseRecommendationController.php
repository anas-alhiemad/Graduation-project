<?php

namespace App\Http\Controllers;


use App\Services\CourseService\CourseRecommendationService;

class CourseRecommendationController extends Controller
{
    protected $recommendationService;

    public function __construct(CourseRecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    public function getRecommendations()
    {
        $studentId = auth()->guard('student')->id();
        return $this->recommendationService->getRecommendations($studentId);
    }
    public function getRecommendationsFromSaved()
{
    $studentId = auth()->guard('student')->id();
    return $this->recommendationService->getRecommendationsFromSaved($studentId);
}
}
