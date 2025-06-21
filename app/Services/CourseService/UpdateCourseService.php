<?php
namespace App\Services\CourseService;

use App\Repositories\CourseRepository;

class UpdateCourseService
{
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function handle($id, $request)
    {
        $course = $this->courseRepository->getById($id);

        if (!$course) {
            return response()->json(["message" => "Course not found"], 404);
        }

        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($course->photo && file_exists(public_path($course->photo))) {
                unlink(public_path($course->photo));
            }
            $data['photo'] = 'upload/' . $request->file('photo')->store('coursePhoto', 'public_upload');
        }

        $updatedCourse = $this->courseRepository->update($id, $data);

        return response()->json([
            "message" => "Course has been updated successfully",
            "course" => $updatedCourse
        ], 200);
    }
}
