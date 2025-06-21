<?php
namespace App\Services\ComplaintService;

use App\Repositories\ComplaintRepository;

class DisplayComplaintService
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
}
