<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ComplaintRequest\CreateComplaintRequest;
use App\Services\ComplaintService\CreateComplaintService;
use App\Services\ComplaintService\DisplayComplaintService;
use App\Services\ComplaintService\DeleteComplaintService;

class ComplaintController extends Controller
{
    protected $createComplaintService;
    protected $displayComplaintService;
    protected $deleteComplaintService;

    public function __construct(
        CreateComplaintService $createComplaintService,
        DisplayComplaintService $displayComplaintService,
        DeleteComplaintService $deleteComplaintService
    ) {
        $this->createComplaintService = $createComplaintService;
        $this->displayComplaintService = $displayComplaintService;
        $this->deleteComplaintService = $deleteComplaintService;
    }

    public function index(Request $request)
    {
        if ($request->has('search')) {
            return $this->displayComplaintService->search($request->search);
        }

        return $this->displayComplaintService->getAll();
    }

    public function show($id)
    {
        return $this->displayComplaintService->getById($id);
    }

    public function store(CreateComplaintRequest $request)
    {
        return $this->createComplaintService->handle($request);
    }

    public function destroy($id)
    {
        return $this->deleteComplaintService->handle($id);
    }
}
