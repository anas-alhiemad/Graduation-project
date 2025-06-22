<?php

namespace App\Services\ReportService;

use App\Repositories\ReportRepository;
use App\Http\Requests\ReportRequest\CreateReportRequest;

class CreateReportService
{
    protected $reportRepository;

    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function handle(CreateReportRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('file')) {
            $data['file'] = 'upload/' . $request->file('file')->store('reports', 'public_upload');
        }

        $data['secretary_id'] = auth()->id();

        $report = $this->reportRepository->create($data);

        return response()->json([
            'message' => 'Report has been created successfully',
            'report' => $report
        ], 200);
    }
}
