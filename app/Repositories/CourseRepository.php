<?php

namespace App\Repositories;

use App\Models\Course;
use App\Repositories\BaseRepository;
use App\Interfaces\RepositoryInterface;

class CourseRepository extends BaseRepository implements RepositoryInterface
{
    public function __construct(Course $model)
    {
        parent::__construct($model);
    }

    public function search($search)
    {
        return $this->model::query()
            ->where('name', 'LIKE', "%{$search}%")
            ->with('department')
            ->paginate(10);
    }

    public function getById($id)
    {
        return $this->model::with('department')->findOrFail($id);
    }

    public function getAll()
    {
        return $this->model::with('department')->paginate(10);
    }
} 