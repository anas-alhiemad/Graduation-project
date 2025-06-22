<?php

namespace App\Services\ReportService;

use App\Repositories\ReportRepository;

class GetReportService
{
    protected $reportRepository;

    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function getAll()
    {
        $reports = $this->reportRepository->getAll();

        return response()->json([
            'message' => 'All reports in the system.',
            'reports' => $reports,
        ]);
    }

    public function getById($id)
    {
        $report = $this->reportRepository->getById($id);

        return response()->json([
            'message' => 'The report details.',
            'report' => $report,
        ]);
    }

    public function getBySecretary($secretaryId)
    {
        $reports = $this->reportRepository->getBySecretary($secretaryId);

        return response()->json([
            'message' => 'All reports for this secretary.',
            'reports' => $reports,
        ]);
    }
}
