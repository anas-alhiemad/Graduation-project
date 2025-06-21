<?php
namespace App\Services\ComplaintService;

use App\Repositories\ComplaintRepository;
use Illuminate\Support\Facades\Storage;

class DeleteComplaintService
{
    protected $complaintRepository;

    public function __construct(ComplaintRepository $complaintRepository)
    {
        $this->complaintRepository = $complaintRepository;
    }

    public function handle($id)
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
