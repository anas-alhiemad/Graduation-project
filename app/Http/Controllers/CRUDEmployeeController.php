<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EmployeeServices\AddEmployeeService;
use App\Services\EmployeeServices\DeleteEmployeeService;
use App\Services\EmployeeServices\UpdateEmployeeService;
use App\Http\Requests\EmployeeRequest\AddEmployeeRequest;
use App\Services\EmployeeServices\DisplayEmployeeService;
use App\Http\Requests\EmployeeRequest\UpdateEmployeeRequest;

class CRUDEmployeeController extends Controller
{
    protected $addEmployeeService;
    protected $updateEmployeeService;
    protected $deleteEmployeeService;
    protected $displayEmployeeService;

    public function __construct(DisplayEmployeeService $displayEmployeeService,AddEmployeeService $addEmployeeService,UpdateEmployeeService $updateEmployeeService,DeleteEmployeeService $deleteEmployeeService)
    {
        $this->addEmployeeService = $addEmployeeService;
        $this->updateEmployeeService = $updateEmployeeService;
        $this->deleteEmployeeService = $deleteEmployeeService;
        $this->displayEmployeeService = $displayEmployeeService;
    }

    public function ShowAllEmployees() 
    {
        return $this->displayEmployeeService->indexEmployees();
    }
    public function ShowEmployeeById($employeeId) 
    {
        return $this->displayEmployeeService->getEmployeeById($employeeId);
    }

    public function AddEmployee(AddEmployeeRequest $request) 
    {
        return $this->addEmployeeService->store($request);
    }

    public function UpdateEmployee($employeeId,UpdateEmployeeRequest $request) 
    {
        return $this->updateEmployeeService->updatingEmployee($employeeId,$request);
    }

    public function DeleteEmployee($employeeId) 
    {
        return $this->deleteEmployeeService->deletingEmployee($employeeId);
    }
    
    public function SearchEmployee($querySearch) 
    {
        return $this->displayEmployeeService->SearchEmployee($querySearch);
    }
}
