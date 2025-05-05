<?php

namespace App\Services\CourseService;
;

use App\Repositories\CourseRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CourseService
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

    public function create($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'state' => 'required|in:not_start,in_progress,finished',
            'department_id' => 'required|exists:departments,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->validated();
        $data['photo'] = 'upload/' . $request->file('photo')->store('coursePhoto', 'public_upload');
        
        $course = $this->courseRepository->create($data);
        return response()->json([
            "message" => "Course has been created successfully",
            "course" => $course
        ], 200);
    }

    public function update($id, $request)
    {
        $course = $this->courseRepository->getById($id);
        
        if (!$course) {
            return response()->json([
                "message" => "Course not found"
            ], 404);
        }

        // Debug the request data
        \Log::info('Course Update Service Data:', [
            'request_all' => $request->all(),
            'validated_data' => $request->validated(),
            'has_name' => $request->has('name'),
            'name_value' => $request->input('name')
        ]);

        $data = $request->validated();
        
        if ($request->hasFile('photo')) {
            if ($course->photo && file_exists(public_path($course->photo))) {
                unlink(public_path($course->photo));
            }
            $data['photo'] = 'upload/' . $request->file('photo')->store('coursePhoto', 'public_upload');
        }
            
        $updatedCourse = $this->courseRepository->update($id, $data);
        
        if (!$updatedCourse) {
            return response()->json([
                "message" => "Failed to update course"
            ], 500);
        }

        return response()->json([
            "message" => "Course has been updated successfully",
            "course" => $updatedCourse
        ], 200);
    }

    public function delete($id)
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

    public function search($query)
    {
        $courses = $this->courseRepository->search($query);
        return response()->json([
            "message" => "Search results for courses",
            "courses" => $courses
        ]);
    }
} 