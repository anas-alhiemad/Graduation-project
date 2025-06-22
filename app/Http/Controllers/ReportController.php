<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest\CreateReportRequest;
use App\Http\Requests\ReportRequest\UpdateReportRequest;
use App\Services\ReportService\CreateReportService;
use App\Services\ReportService\UpdateReportService;
use App\Services\ReportService\DeleteReportService;
use App\Services\ReportService\GetReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $createReportService;
    protected $updateReportService;
    protected $deleteReportService;
    protected $getReportService;

    public function __construct(
        CreateReportService $createReportService,
        UpdateReportService $updateReportService,
        DeleteReportService $deleteReportService,
        GetReportService $getReportService
    ) {
        $this->createReportService = $createReportService;
        $this->updateReportService = $updateReportService;
        $this->deleteReportService = $deleteReportService;
        $this->getReportService = $getReportService;
    }

    public function index()
    {
        return $this->getReportService->getAll();
    }

    public function show($id)
    {
        return $this->getReportService->getById($id);
    }

    public function store(CreateReportRequest $request)
    {
        return $this->createReportService->handle($request);
    }

    public function update(UpdateReportRequest $request, $id)
    {
        return $this->updateReportService->handle($id, $request);
    }

    public function destroy($id)
    {
        return $this->deleteReportService->handle($id);
    }

    public function getBySecretary($secretaryId = null)
    {
        return $this->getReportService->getBySecretary($secretaryId ?? auth()->id());
    }
}
