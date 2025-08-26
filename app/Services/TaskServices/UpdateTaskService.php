<?php

namespace App\Services\TaskServices;

use App\Repositories\TaskRepository;
use App\Repositories\SecretaryRepository;
use App\Services\NotificationServices\SendNotificationsService;

class UpdateTaskService
{
    protected $taskRepository;
    protected $sendNotificationsService;
    protected $secretaryRepository;

    public function __construct(TaskRepository $taskRepository, SendNotificationsService $sendNotificationsService,SecretaryRepository  $secretaryRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->secretaryRepository = $secretaryRepository;
        $this->sendNotificationsService = $sendNotificationsService;
    }

    public function updateTask($task_id,$request)
    {
    
        $task = $this->taskRepository->update($task_id,$request->all());

        $secretary = $task->secretaries;

        $title = "Your assigned task has been modified.";
        $body = "New task title: " . $task->title;

        if ($secretary->fcm_token) {
            $this->sendNotificationsService->sendByFcm($secretary->fcm_token, [
                'title' => $title,
                'body' => $body,
            ]);

            $this->sendNotificationsService->storeNotification($secretary, $title, $body);
        }

        return response()->json([
            'message' => 'The task was successfully updated.',
            'task' => $task
        ], 200);
    }


    public function updateStatus($task_id, $newStatus)
    {
        
        $task = $this->taskRepository->update($task_id, [
            'status' => $newStatus
        ]);

    if ($newStatus === 'completed') {
        $this->secretaryRepository->incrementPoint(auth()->guard('secretary')->id());
    }
        return response()->json([
            'message' => 'Task status updated successfully.',
            'task' => $task
        ], 200);
    }

}
