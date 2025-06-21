<?php
namespace App\Services\DepartmentServices;

use App\Repositories\DepartmentRepository;
use Illuminate\Support\Facades\Storage;

class DeleteDepartmentService
{
    protected $departmentRepository;

    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function handle($id)
    {
        $department = $this->departmentRepository->getById($id);

        if ($department->photo) {
            Storage::disk('public_upload')->delete(str_replace('upload/', '', $department->photo));
        }

        $this->departmentRepository->delete($id);

        return response()->json([
            'message' => 'Department has been deleted successfully'
        ]);
    }
}
