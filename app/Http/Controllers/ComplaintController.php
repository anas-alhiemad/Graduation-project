<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ComplaintService\ComplaintService;

class ComplaintController extends Controller
{
    protected $complaintService;

    public function __construct(ComplaintService $complaintService)
    {
        $this->complaintService = $complaintService;
    }

    public function index(Request $request)
    {
        if ($request->has('search')) {
            return $this->complaintService->search($request->search);
        }
        return $this->complaintService->getAll();
    }

    public function show($id)
    {
        return $this->complaintService->getById($id);
    }

    public function store(Request $request)
    {
        return $this->complaintService->create($request);
    }

    public function destroy($id)
    {
        return $this->complaintService->delete($id);
    }
} 