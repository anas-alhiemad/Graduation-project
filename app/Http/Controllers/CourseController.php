<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Services\CourseService\CreateCourseService;
use App\Services\CourseService\UpdateCourseService;
use App\Services\CourseService\DeleteCourseService;
use App\Services\CourseService\DisplayCourseService;
use App\Http\Requests\CourseRequest\CreateCourseRequest;
use App\Http\Requests\CourseRequest\CourseRequest as UpdateCourseRequest;


class CourseController extends Controller
{
    protected $createService;
    protected $updateService;
    protected $deleteService;
    protected $displayService;

    public function __construct(
        CreateCourseService $createService,
        UpdateCourseService $updateService,
        DeleteCourseService $deleteService,
        DisplayCourseService $displayService
    ) {
        $this->createService = $createService;
        $this->updateService = $updateService;
        $this->deleteService = $deleteService;
        $this->displayService = $displayService;
    }

    public function index()
    {
        return $this->displayService->getAll();
    }

    public function show($id)
    {
        return $this->displayService->getById($id);
    }

    public function store(CreateCourseRequest $request)
    {
        return $this->createService->handle($request);
    }

   
public function update(UpdateCourseRequest $request, $id)
{
    return $this->updateService->handle($id, $request);
}

    public function destroy($id)
    {
        return $this->deleteService->handle($id);
    }

    public function search($query)
    {
        return $this->displayService->search($query);
    }

    public function getByDepartment($departmentId)
    {
        return $this->displayService->getByDepartment($departmentId);
    }
}
