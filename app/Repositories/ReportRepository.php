<?php

namespace App\Repositories;

use App\Models\Report;
use App\Repositories\BaseRepository;
use App\Interfaces\RepositoryInterface;

class ReportRepository extends BaseRepository implements RepositoryInterface
{
    public function __construct(Report $model)
    {
        parent::__construct($model);
    }

    public function getById($id)
    {
        return $this->model::with('secretary')->findOrFail($id);
    }

    public function getAll()
    {
        return $this->model::with('secretary')->paginate(10);
    }

    public function getBySecretary($secretaryId)
    {
        return $this->model::where('secretary_id', $secretaryId)
            ->with('secretary')
            ->paginate(10);
    }
} 