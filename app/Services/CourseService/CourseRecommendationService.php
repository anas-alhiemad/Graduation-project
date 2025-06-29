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
        // جلب كل الكورسات التي سجل فيها الطالب فعليًا
        $studentCourses = $this->courseSectionRepository
            ->getStudentCourses($studentId)
            ->pluck('course');

        // استخراج كل الأقسام التي درس فيها الطالب
        $departmentIds = $studentCourses->pluck('department.id')->unique();

        // استخراج كل الكورسات الأخرى في نفس الأقسام، واستثناء التي درسها الطالب
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
    // جلب الكورسات التي حفظها الطالب مع الأقسام
    $savedCourses = Student::with('savedCourses.department')->findOrFail($studentId)->savedCourses;

    // جلب أقسام الكورسات المحفوظة
    $departmentIds = $savedCourses->pluck('department.id')->unique();

    // جلب كورسات من نفس الأقسام، غير محفوظة
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
