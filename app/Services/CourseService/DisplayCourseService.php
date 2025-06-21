<?php
namespace App\Services\CourseService;

use App\Repositories\CourseRepository;

class DisplayCourseService
{
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function getAll()
    {
        $courses = $this->courseRepository->getAll();
        return response()->json([
            "message" => "All courses in the System.",
            "courses" => $courses
        ]);
    }

    public function getById($id)
    {
        $course = $this->courseRepository->getById($id);
        return response()->json([
            "message" => "The course details.",
            "course" => $course
        ]);
    }

    public function search($query)
    {
        $courses = $this->courseRepository->search($query);
        return response()->json([
            "message" => "Search results for courses",
            "courses" => $courses
        ]);
    }

    public function getByDepartment($departmentId)
    {
        $courses = $this->courseRepository->getByDepartment($departmentId);
        return response()->json([
            "message" => "Courses in the department",
            "courses" => $courses
        ]);
    }
}
