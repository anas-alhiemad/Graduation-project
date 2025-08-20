<?php

namespace App\Services\CourseService;

use App\Models\Course;

class CourseStatisticsService
{
    /**
     * إرجاع أكثر الكورسات إقبالًا
     * @param int $limit عدد الكورسات المراد إرجاعها
     */
    public function getTopCourses(int $limit = 5)
    {
        $courses = Course::with(['courseSection.students' => function ($query) {
            $query->wherePivot('is_confirmed', true);
        }])->get();

        // حساب مجموع الطلاب لكل كورس عبر Sections
        $courses = $courses->map(function ($course) {
            $total = $course->courseSection->sum(function ($section) {
                return $section->students->count();
            });
            return [
                'course_id' => $course->id,
                'course_name' => $course->name,
                'total_students' => $total,
            ];
        });

        // ترتيب تنازلي وأخذ العدد المحدد
        return $courses->sortByDesc('total_students')->take($limit)->values();
    }
}
