<?php

namespace App\Services\CourseService;

use App\Repositories\CourseRepository;



class CreateCourseService
{
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function handle($request)
    {
        $data = $request->validated();
        $data['photo'] = 'upload/' . $request->file('photo')->store('coursePhoto', 'public_upload');

        $course = $this->courseRepository->create($data);

        return response()->json([
            "message" => "Course has been created successfully",
            "course" => $course
        ], 200);
    }
}
