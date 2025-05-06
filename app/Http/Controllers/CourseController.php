<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CourseService\CourseService;
use App\Http\Requests\CourseRequest\CourseRequest;
use App\Http\Requests\CourseRequest\CreateCourseRequest;

class CourseController extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function index()
    {
        return $this->courseService->getAll();
    }

    public function show($id)
    {
        return $this->courseService->getById($id);
    }

    public function store(CreateCourseRequest $request)
    {
        return $this->courseService->create($request);
    }

    public function update(CourseRequest $request, $id)
    {
        return $this->courseService->update($id, $request);
    }

    public function destroy($id)
    {
        return $this->courseService->delete($id);
    }

    public function search($query)
    {
        return $this->courseService->search($query);
    }
} 