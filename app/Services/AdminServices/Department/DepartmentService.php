<?php

namespace App\Services\AdminServices\Department;

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
        return $this->departmentRepository->getAll();
    }

    public function getById($id)
    {
        return $this->departmentRepository->getById($id);
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
        
        return $this->departmentRepository->create($data);
    }

    public function update($id, $request)
    {
        // Log the incoming request data
        Log::info('Update Request Data:', [
            'all' => $request->all(),
            'validated' => $request->validated(),
            'has_name' => $request->has('name'),
            'name_value' => $request->input('name'),
            'method' => $request->method()
        ]);

        // Get the current department
        $department = $this->departmentRepository->getById($id);
        
        // Get validated data
        $data = $request->validated();
        
        // Handle photo update if a new photo is provided
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($department->photo) {
                Storage::disk('public_upload')->delete(str_replace('upload/', '', $department->photo));
            }
            $data['photo'] = 'upload/' . $request->file('photo')->store('departmentPhoto', 'public_upload');
        }

        // Log the final update data
        Log::info('Final update data:', $data);

        // Only update if there are changes
        if (!empty($data)) {
            $updated = $this->departmentRepository->update($id, $data);
            Log::info('Update result:', ['updated' => $updated]);
            return $updated;
        }

        return $department;
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
        return $this->departmentRepository->delete($id);
    }
} 