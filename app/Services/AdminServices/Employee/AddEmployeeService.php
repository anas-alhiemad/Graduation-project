<?php
namespace App\Services\AdminServices\Employee;

use App\Repositories\EmployeeRepository;

class AddEmployeeService 
{

    protected $employeeRepository;
    
    public function __construct(EmployeeRepository  $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function store
}