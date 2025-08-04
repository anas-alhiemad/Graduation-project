<?php
namespace App\Services\TaskServices;

use App\Repositories\TaskRepository;
use Illuminate\Support\Facades\Auth;

class DisplayTaskService 
{
    protected $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getAllTasksForAdmin()
    {
       $tasks = $this->taskRepository->getAllTask();
       return response()->json(["Message" => "All Task in system.","Tasks" => $tasks]);
    }

    public function getTasksByIdSecretary($secretary_id)
    {
       $tasks = $this->taskRepository->getTask($secretary_id);
       return response()->json(["Message" => "All Task for this One in system.","Tasks" => $tasks]);
    }

    public function getMyTasks()
    {
       $secretary_id = Auth()->guard('secretary')->id(); 
       $tasks = $this->taskRepository->getTask($secretary_id);
       return response()->json(["Message" => "All your Task in system.","Tasks" => $tasks]);
    }
}