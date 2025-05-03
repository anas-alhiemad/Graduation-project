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


    public function search($search)
    {
       
        $employees = $this->model::query()
            ->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('role', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->paginate(10);

        return $employees;
    }
}