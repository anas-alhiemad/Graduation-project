<?php
namespace App\Services\AdService;

use Illuminate\Http\JsonResponse;
use App\Repositories\AdRepository;
use App\Repositories\StudentRepository;
use Illuminate\Support\Facades\Storage;
use App\Services\NotificationServices\SendNotificationsService;

class CreateAdService
{
    protected $adRepository;
    protected $studentRepository;
    protected $sendNotificationsService;

    public function __construct(AdRepository $adRepository,StudentRepository $studentRepository,SendNotificationsService $sendNotificationsService)
    {
        $this->adRepository = $adRepository;
        $this->studentRepository = $studentRepository;
        $this->sendNotificationsService = $sendNotificationsService;
    }

   public function handle($request): JsonResponse
{
    $data = $request->validated();

    if ($request->hasFile('photo')) {
        
        $data['photo'] = $this->uploadPhoto($request->file('photo'));
    }

    $ad = $this->adRepository->create($data);

    $students = $this->studentRepository->studentsHaveFcmToken();
    foreach ($students as $student) 
    {
                if ($student->fcm_token) {
                    $title = "New ad";
                    $body  = "A new ad has been added:" . $ad->title;

                    $this->sendNotificationsService->sendByFcm($student->fcm_token, [
                        'title' => $title,
                        'body' => $body,
                    ]);

                    $this->sendNotificationsService->storeNotification($student, $title, $body);
                }
        }


    return response()->json([
        'status' => 'success',
        'message' => 'Ad created successfully',
        'data' => $ad
    ], 201);
}

       protected function uploadPhoto($photo): string
    {
        return 'upload/' . $photo->store('ads', 'public_upload');
    }
}
