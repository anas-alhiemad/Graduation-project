<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Interfaces\RepositoryInterface;
use App\Models\Task;

class TaskRepository extends BaseRepository implements RepositoryInterface
{
    public function __construct(Task $model)
    {
        parent::__construct($model);
    }

    public function getAllTask()
    {
        return $this->model::with('secretaries')->paginate(10);
    }

    public function getTask($secretary_id)
    {
        return $this->model::where('secretary_id', $secretary_id)->paginate(10);
    }
}