<?php
namespace App\Services\EmployeeServices;

use App\Repositories\EmployeeRepository;

class DisplayEmployeeService 
{
    protected $employeeRepository;
    
    public function __construct(EmployeeRepository  $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function indexEmployees()
    {
        $employees = $this->employeeRepository->getAll();
        return response()->json([
            "message" => "All employees in the System.",
            "Employees" => $employees]);
    }
    
    public function getEmployeeById($employeeId)
    {
        $employee = $this->employeeRepository->getById($employeeId);
        return response()->json([
            "message" => "the employee .",
            "Employee" => $employee]);
    }


    public function searchEmployee($request)
    {

        $employees = $this->employeeRepository->search($request);

        return response()->json(["message"=>"Search results","Employees" => $employees]);
    }

}