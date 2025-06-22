<?php

namespace App\Services\ReportService;

use App\Repositories\ReportRepository;
use App\Http\Requests\ReportRequest\UpdateReportRequest;

class UpdateReportService
{
    protected $reportRepository;

    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function handle($id, UpdateReportRequest $request)
    {
        $report = $this->reportRepository->getById($id);

        $data = $request->validated();

        if ($request->hasFile('file')) {
            if ($report->file && file_exists(public_path($report->file))) {
                unlink(public_path($report->file));
            }
            $data['file'] = 'upload/' . $request->file('file')->store('reports', 'public_upload');
        }

        $updatedReport = $this->reportRepository->update($id, $data);

        if (!$updatedReport) {
            return response()->json([
                'message' => 'Failed to update report'
            ], 500);
        }

        return response()->json([
            'message' => 'Report has been updated successfully',
            'report' => $updatedReport
        ], 200);
    }
}
