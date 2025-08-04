<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TaskServices\CreateTaskService;
use App\Services\TaskServices\DeleteTaskService;
use App\Services\TaskServices\UpdateTaskService;
use App\Services\TaskServices\DisplayTaskService;
use App\Http\Requests\TaskRequest\CreateTaskRequest;
use App\Http\Requests\TaskRequest\UpdateTaskRequest;
use App\Http\Requests\TaskRequest\UpdateStatusTaskRequest;

class TaskController extends Controller
{
    protected $createTaskService;
    protected $displayTaskService;
    protected $updateTaskService;
    protected $deleteTaskService;

    public function __construct(CreateTaskService $createTaskService,DisplayTaskService $displayTaskService,UpdateTaskService $updateTaskService,DeleteTaskService $deleteTaskService)
    {
        $this->createTaskService = $createTaskService;
        $this->displayTaskService = $displayTaskService;
        $this->updateTaskService = $updateTaskService;
        $this->deleteTaskService = $deleteTaskService;
    }


    public function CreateTask(CreateTaskRequest $request) 
    {
        return $this->createTaskService->store($request);
    }

    
    public function ShowTasksForAdmin()
    {
        return $this->displayTaskService->getAllTasksForAdmin();
    }

    public function ShowTasksByIdSecretary($secretary_id)
    {
        return $this->displayTaskService->getTasksByIdSecretary($secretary_id);
    }

    public function ShowMyTask()
    {
        return $this->displayTaskService->getMyTasks();
    }

    public function UpdateTask( $task_id,UpdateTaskRequest $request)
    {
        return $this->updateTaskService->updateTask($task_id,$request);
    }

    public function ChangeStatus($task_id,UpdateStatusTaskRequest $request)
    {
        $newStatus = $request->input('status');
        return $this->updateTaskService->updateStatus($task_id,$newStatus);
    }

    public function DeleteTask($task_id)
    {
        return $this->deleteTaskService->deleteTask($task_id);
    }

}

