<?php
namespace App\Services\DepartmentServices;

use App\Repositories\DepartmentRepository;

class DisplayAllDepartmentsService
{
    protected $departmentRepository;

    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function handle()
    {
        $departments = $this->departmentRepository->getAll();
        return response()->json([
            "message" => "All departments in the System.",
            "departments" => $departments
        ]);
    }
}
