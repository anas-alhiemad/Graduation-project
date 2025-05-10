<?php

namespace App\Services\ReportService;

use App\Repositories\ReportRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ReportService
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
            "message" => "All reports in the System.",
            "reports" => $reports
        ]);
    }

    public function getById($id)
    {
        $report = $this->reportRepository->getById($id);
        return response()->json([
            "message" => "The report details.",
            "report" => $report
        ]);
    }

    public function getBySecretary($secretaryId)
    {
        $reports = $this->reportRepository->getBySecretary($secretaryId);
        return response()->json([
            "message" => "All reports for this secretary.",
            "reports" => $reports
        ]);
    }

    public function create($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'file' => 'required|file|max:10240' // 10MB max
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->validated();
        $data['file'] = 'upload/' . $request->file('file')->store('reports', 'public_upload');
        $data['secretary_id'] = auth()->id();
        
        $report = $this->reportRepository->create($data);
        return response()->json([
            "message" => "Report has been created successfully",
            "report" => $report
        ], 200);
    }

    public function update($id, $request)
    {
        $report = $this->reportRepository->getById($id);
        
        if (!$report) {
            return response()->json([
                "message" => "Report not found"
            ], 404);
        }

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
                "message" => "Failed to update report"
            ], 500);
        }

        return response()->json([
            "message" => "Report has been updated successfully",
            "report" => $updatedReport
        ], 200);
    }

    public function delete($id)
    {
        $report = $this->reportRepository->getById($id);
        
        if ($report->file && file_exists(public_path($report->file))) {
            unlink(public_path($report->file));
        }
        
        $this->reportRepository->delete($id);
        
        return response()->json([
            'message' => 'Report has been deleted successfully'
        ], 200);
    }
} 