<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportService\ReportService;
use App\Http\Requests\ReportRequest\ReportRequest;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        return $this->reportService->getAll();
    }

    public function show($id)
    {
        return $this->reportService->getById($id);
    }

    public function store(ReportRequest $request)
    {
        return $this->reportService->create($request);
    }

    public function update(ReportRequest $request, $id)
    {
        return $this->reportService->update($id, $request);
    }

    public function destroy($id)
    {
        return $this->reportService->delete($id);
    }

    public function getBySecretary($secretaryId = null)
    {
        if ($secretaryId === null) {
            $secretaryId = auth()->id();
        }
        return $this->reportService->getBySecretary($secretaryId);
    }
} 