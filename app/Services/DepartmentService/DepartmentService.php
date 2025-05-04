<?php

namespace App\Services\DepartmentService;

use App\Repositories\DepartmentRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DepartmentService
{
    protected $departmentRepository;

    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function getAll()
    {
        $departments = $this->departmentRepository->getAll();
        return response()->json([
            "message" => "All departments in the System.",
            "departments" => $departments
        ]);
    }

    public function getById($id)
    {
        $department = $this->departmentRepository->getById($id);
        return response()->json([
            "message" => "The department details.",
            "department" => $department
        ]);
    }

    public function create($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->validated();
        $data['photo'] = 'upload/' . $request->file('photo')->store('departmentPhoto', 'public_upload');
        
        $department = $this->departmentRepository->create($data);
        return response()->json([
            "message" => "Department has been created successfully",
            "department" => $department
        ], 200);
    }

    public function update($id, $request)
    {
        $department = $this->departmentRepository->getById($id);
        
        if (!$department) {
            return response()->json([
                "message" => "Department not found"
            ], 404);
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
            return response()->json([
                "message" => "Failed to update department"
            ], 500);
        }

        return response()->json([
            "message" => "Department has been updated successfully",
            "department" => $updatedDepartment
        ], 200);
    }

    public function delete($id)
    {
        // Get the department first
        $department = $this->departmentRepository->getById($id);
        
        // Delete the photo file
        if ($department->photo) {
            Storage::disk('public_upload')->delete(str_replace('upload/', '', $department->photo));
        }
        
        // Delete the department record
        $this->departmentRepository->delete($id);
        
        return response()->json([
            'message' => 'Department has been deleted successfully'
        ], 200);
    }
} 