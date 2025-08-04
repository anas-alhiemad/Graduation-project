<?php

namespace App\Services\TaskServices;

use App\Repositories\TaskRepository;
use App\Services\NotificationServices\SendNotificationsService;

class UpdateTaskService
{
    protected $taskRepository;
    protected $sendNotificationsService;

    public function __construct(TaskRepository $taskRepository, SendNotificationsService $sendNotificationsService)
    {
        $this->taskRepository = $taskRepository;
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

        return response()->json([
            'message' => 'Task status updated successfully.',
            'task' => $task
        ], 200);
    }

}
