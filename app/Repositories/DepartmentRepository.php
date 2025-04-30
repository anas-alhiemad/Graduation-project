<?php

namespace App\Repositories;

use App\Models\Department;
use App\Interfaces\RepositoryInterface;

class DepartmentRepository extends BaseRepository implements RepositoryInterface
{
    public function __construct(Department $model)
    {
        parent::__construct($model);
    }
} 