<?php
namespace App\Services\DepartmentServices;

use App\Repositories\DepartmentRepository;
use Illuminate\Support\Facades\Storage;

class UpdateDepartmentService
{
    protected $departmentRepository;

    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function handle($id, $request)
    {
        $department = $this->departmentRepository->getById($id);

        if (!$department) {
            return response()->json(["message" => "Department not found"], 404);
        }

        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($department->photo && file_exists(public_path($department->photo))) {
                unlink(public_path($department->photo));
            }

            $data['photo'] = 'upload/' . $request->file('photo')->store('departmentPhoto', 'public_upload');
        }

        $updatedDepartment = $this->departmentRepository->update($id, $data);

        if (!$updatedDepartment) {
            return response()->json(["message" => "Failed to update department"], 500);
        }

        return response()->json([
            "message" => "Department has been updated successfully",
            "department" => $updatedDepartment
        ]);
    }
}
