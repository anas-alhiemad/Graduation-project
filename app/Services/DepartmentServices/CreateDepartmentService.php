<?php
namespace App\Services\DepartmentServices;

use App\Repositories\DepartmentRepository;

class CreateDepartmentService
{
    protected $departmentRepository;

    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function handle($request)
    {
        $data = $request->validated();
        $data['photo'] = 'upload/' . $request->file('photo')->store('departmentPhoto', 'public_upload');

        $department = $this->departmentRepository->create($data);

        return response()->json([
            "message" => "Department has been created successfully",
            "department" => $department
        ], 200);
    }
}
