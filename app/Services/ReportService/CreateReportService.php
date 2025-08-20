<?php

namespace App\Services\ReportService;

use App\Repositories\AdminRepository;
use App\Repositories\ReportRepository;
use App\Http\Requests\ReportRequest\CreateReportRequest;
use App\Services\NotificationServices\SendNotificationsService;

class CreateReportService
{
    protected $reportRepository;
    protected $adminRepository;
    protected $sendNotificationsService;

    public function __construct(ReportRepository $reportRepository,SendNotificationsService $sendNotificationsService,AdminRepository $adminRepository)
    {
        $this->reportRepository = $reportRepository;
        $this->adminRepository = $adminRepository;
        $this->sendNotificationsService = $sendNotificationsService;
    }

    public function handle(CreateReportRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('file')) {
            $data['file'] = 'upload/' . $request->file('file')->store('reports', 'public_upload');
        }

        $data['secretary_id'] = auth()->guard('secretary')->id();

        $report = $this->reportRepository->create($data);
        $admins = $this->adminRepository->adminsHaveFcmToken();
        
        foreach ($admins as $admin) {
                    if ($admin->fcm_token) {
                        $title = "New report uploaded";
                        $body  = "A new report has been uploaded";

                        
                        $this->sendNotificationsService->sendByFcm($admin->fcm_token, [
                            'title' => $title,
                            'body' => $body,
                        ]);

                       
                        $this->sendNotificationsService->storeNotification($admin, $title, $body);
                    }
        }

        return response()->json([
            'message' => 'Report has been created successfully',
            'report' => $report
        ], 200);
    }
}