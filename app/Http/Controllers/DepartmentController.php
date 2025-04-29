<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AdminServices\Department\DepartmentService;
use App\Http\Requests\DepartmentRequest\DepartmentRequest;

class DepartmentController extends Controller
{
    protected $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    public function index()
    {
        return $this->departmentService->getAll();
    }

    public function show($id)
    {
        return $this->departmentService->getById($id);
    }

    public function store(DepartmentRequest $request)
    {
        return $this->departmentService->create($request);
    }

    public function update(DepartmentRequest $request, $id)
    {
        return $this->departmentService->update($id, $request);
    }

    public function destroy($id)
    {
        return $this->departmentService->delete($id);
    }
} 