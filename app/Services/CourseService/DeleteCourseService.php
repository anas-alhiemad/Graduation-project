<?php
namespace App\Services\CourseService;

use App\Repositories\CourseRepository;

class DeleteCourseService
{
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function handle($id)
    {
        $course = $this->courseRepository->getById($id);

        if ($course->photo && file_exists(public_path($course->photo))) {
            unlink(public_path($course->photo));
        }

        $this->courseRepository->delete($id);

        return response()->json([
            'message' => 'Course has been deleted successfully'
        ], 200);
    }
}
