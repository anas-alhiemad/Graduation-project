<?php

namespace App\Services\TaskServices;

use App\Models\Task;
use App\Repositories\TaskRepository;

class DeleteTaskService
{
    protected $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function deleteTask($task_id)
    {

        $this->taskRepository->delete($task_id);
        return response()->json(['message' => 'Task deleted successfully.'],200);
    }
}
