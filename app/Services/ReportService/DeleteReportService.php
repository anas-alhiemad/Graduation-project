<?php

namespace App\Services\ReportService;

use App\Repositories\ReportRepository;
use Illuminate\Support\Facades\File;

class DeleteReportService
{
    protected $reportRepository;

    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function handle($id)
    {
        $report = $this->reportRepository->getById($id);

        if ($report->file && file_exists(public_path($report->file))) {
            File::delete(public_path($report->file));
        }

        $this->reportRepository->delete($id);

        return response()->json([
            'message' => 'Report has been deleted successfully'
        ], 200);
    }
}
