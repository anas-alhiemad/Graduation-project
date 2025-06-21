<?php

namespace App\Services\ComplaintService;

use App\Repositories\ComplaintRepository;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ComplaintRequest\CreateComplaintRequest;

class CreateComplaintService
{
    protected $complaintRepository;

    public function __construct(ComplaintRepository $complaintRepository)
    {
        $this->complaintRepository = $complaintRepository;
    }

    public function handle(CreateComplaintRequest $request)
    {
        $data = [
            'description' => $request->description,
            'student_id'  => auth()->user()->id,
        ];

        if ($request->hasFile('file')) {
            $data['file_path'] = 'upload/' . $request->file('file')->store('complaints', 'public_upload');
        }

        $complaint = $this->complaintRepository->create($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Complaint has been submitted successfully',
            'data'    => $complaint,
        ], 201);
    }
}
