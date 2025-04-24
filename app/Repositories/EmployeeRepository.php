<?php
namespace App\Repositories;

use App\Models\Employee;
use App\Repositories\BaseRepository;
use App\Interfaces\RepositoryInterface;

class EmployeeRepository extends BaseRepository implements RepositoryInterface
{
    public function __construct(Employee $model)
    {
        parent::__construct($model);
    }
}