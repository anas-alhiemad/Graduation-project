<?php

namespace App\Repositories;

use App\Models\SavedCourse;
use App\Models\Student;
use App\Models\Course;

class SavedCourseRepository
{
    public function saveCourse($studentId, $courseId)
    {
        return SavedCourse::create([
            'student_id' => $studentId,
            'course_id' => $courseId
        ]);
    }

    public function unsaveCourse($studentId, $courseId)
    {
        return SavedCourse::where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->delete();
    }

    public function getStudentSavedCourses($studentId, $perPage = 10)
    {
        return Course::whereHas('savedByStudents', function ($query) use ($studentId) {
            $query->where('students.id', $studentId);
        })->paginate($perPage);
    }

    public function isCourseSaved($studentId, $courseId)
    {
        return SavedCourse::where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->exists();
    }

    public function getSavedCoursesCount($studentId)
    {
        return SavedCourse::where('student_id', $studentId)->count();
    }
} 