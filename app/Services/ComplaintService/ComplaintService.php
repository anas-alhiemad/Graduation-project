<?php

namespace App\Services\ComplaintService;

use App\Repositories\ComplaintRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ComplaintService
{
    protected $complaintRepository;

    public function __construct(ComplaintRepository $complaintRepository)
    {
        $this->complaintRepository = $complaintRepository;
    }

    public function getAll()
    {
        $complaints = $this->complaintRepository->getAll();
        return response()->json([
            "status" => "success",
            "message" => "Complaints retrieved successfully",
            "data" => $complaints
        ]);
    }

    public function getById($id)
    {
        $complaint = $this->complaintRepository->getById($id);
        return response()->json([
            "status" => "success",
            "message" => "Complaint details retrieved successfully",
            "data" => $complaint
        ]);
    }

    public function getByStudentId($studentId)
    {
        $complaints = $this->complaintRepository->getByStudentId($studentId);
        return response()->json([
            "message" => "Student's complaints retrieved successfully.",
            "complaints" => $complaints
        ]);
    }

    public function search($query)
    {
        $complaints = $this->complaintRepository->search($query);
        return response()->json([
            "status" => "success",
            "message" => "Search results retrieved successfully",
            "data" => $complaints
        ]);
    }

    public function create($request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|min:10',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "message" => "Validation failed",
                "errors" => $validator->errors()
            ], 422);
        }

        $data = [
            'description' => $request->description
        ];

        if ($request->hasFile('file')) {
            $data['file_path'] = 'upload/' . $request->file('file')->store('complaints', 'public_upload');
        }

        $complaint = $this->complaintRepository->create($data);
        return response()->json([
            "status" => "success",
            "message" => "Complaint has been submitted successfully",
            "data" => $complaint
        ], 201);
    }

    public function delete($id)
    {
        $complaint = $this->complaintRepository->getById($id);
        
        if ($complaint->file_path) {
            Storage::disk('public_upload')->delete(str_replace('upload/', '', $complaint->file_path));
        }
        
        $this->complaintRepository->delete($id);
        
        return response()->json([
            "status" => "success",
            "message" => "Complaint has been deleted successfully"
        ], 200);
    }
} 