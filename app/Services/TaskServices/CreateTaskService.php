<?php
namespace App\Services\TaskServices;

use App\Repositories\TaskRepository;
use App\Services\NotificationServices\SendNotificationsService;

class CreateTaskService 
{
    protected $taskRepository;
    protected $sendNotificationsService;

    public function __construct(TaskRepository $taskRepository,SendNotificationsService $sendNotificationsService)
    {
        $this->taskRepository = $taskRepository;
        $this->sendNotificationsService = $sendNotificationsService;
    }

    public function store($request) 
    {
        $dataTask = $request->all();
        $task = $this->taskRepository->create($dataTask);
        $secretary = $task->secretaries;
        $title = "You have been assigned a new task.";
        $body = "Task title: " . $request->title;
        if ($secretary->fcm_token) {
        $this->sendNotificationsService->sendByFcm($secretary->fcm_token, [
            'title' => $title,
            'body' => $body,
        ]);

        $this->sendNotificationsService->storeNotification($secretary, $title, $body);}
        return response()->json(['message' => 'The task was successfully assigned.','Task' => $task],200);
    }
}

