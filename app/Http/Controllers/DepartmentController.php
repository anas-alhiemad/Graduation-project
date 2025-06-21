<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DepartmentRequest\DepartmentRequest;
use App\Services\DepartmentServices\CreateDepartmentService;
use App\Services\DepartmentServices\UpdateDepartmentService;
use App\Services\DepartmentServices\DeleteDepartmentService;
use App\Services\DepartmentServices\DisplayAllDepartmentsService;
use App\Services\DepartmentServices\DisplayDepartmentByIdService;

class DepartmentController extends Controller
{
    public function index(DisplayAllDepartmentsService $service)
    {
        return $service->handle();
    }

    public function show($id, DisplayDepartmentByIdService $service)
    {
        return $service->handle($id);
    }

    public function store(DepartmentRequest $request, CreateDepartmentService $service)
    {
        return $service->handle($request);
    }

    public function update(DepartmentRequest $request, $id, UpdateDepartmentService $service)
    {
        return $service->handle($id, $request);
    }

    public function destroy($id, DeleteDepartmentService $service)
    {
        return $service->handle($id);
    }
}
