<?php
namespace App\Services\DepartmentServices;

use App\Repositories\DepartmentRepository;

class DisplayDepartmentByIdService
{
    protected $departmentRepository;

    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function handle($id)
    {
        $department = $this->departmentRepository->getById($id);
        return response()->json([
            "message" => "The department details.",
            "department" => $department
        ]);
    }
}
