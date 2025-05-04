<?php

namespace App\Repositories;

use App\Models\Complaint;
use App\Repositories\BaseRepository;
use App\Interfaces\RepositoryInterface;

class ComplaintRepository extends BaseRepository implements RepositoryInterface
{
    public function __construct(Complaint $model)
    {
        parent::__construct($model);
    }

    public function getById($id)
    {
        return $this->model::findOrFail($id);
    }

    public function getAll()
    {
        return $this->model::orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function search($query)
    {
        return $this->model::where('description', 'LIKE', "%{$query}%")
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }
} 