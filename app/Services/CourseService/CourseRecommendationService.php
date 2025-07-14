<?php

namespace App\Services\CourseService;

use App\Repositories\CourseRepository;
use App\Repositories\CourseSectionRepository;
use App\Models\Student;
class CourseRecommendationService
{
    protected $courseSectionRepository;
    protected $courseRepository;

    public function __construct(
        CourseSectionRepository $courseSectionRepository,
        CourseRepository $courseRepository
    ) {
        $this->courseSectionRepository = $courseSectionRepository;
        $this->courseRepository = $courseRepository;
    }

    public function getRecommendations($studentId)
    {
        
        $studentCourses = $this->courseSectionRepository
            ->getStudentCourses($studentId)
            ->pluck('course');

    
        $departmentIds = $studentCourses->pluck('department.id')->unique();

        
        $recommendedCourses = $this->courseRepository
            ->getCoursesByDepartmentIds($departmentIds)
            ->whereNotIn('id', $studentCourses->pluck('id'))
            ->values();

        return response()->json([
            'message' => 'Recommended courses based on your past enrollment',
            'recommended_courses' => $recommendedCourses
        ]);
        
    }
    

public function getRecommendationsFromSaved($studentId)
{
    
    $savedCourses = Student::with('savedCourses.department')->findOrFail($studentId)->savedCourses;

    
    $departmentIds = $savedCourses->pluck('department.id')->unique();

    
    $recommendedCourses = $this->courseRepository
        ->getCoursesByDepartmentIds($departmentIds)
        ->whereNotIn('id', $savedCourses->pluck('id'))
        ->values();

    return response()->json([
        'message' => 'Recommended courses based on your saved courses',
        'recommended_courses' => $recommendedCourses
    ]);
}
}
