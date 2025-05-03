<?php
namespace App\Services\EmployeeServices;

use App\Repositories\EmployeeRepository;
use App\Repositories\SecretaryRepository;

class DeleteEmployeeService 
{
    protected $employeeRepository;
    protected $secretaryRepository;
    public function __construct(EmployeeRepository  $employeeRepository,SecretaryRepository  $secretaryRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->secretaryRepository = $secretaryRepository;
    }


    public function deletingEmployee($employeeId)
    {
        $employee = $this->employeeRepository->getById($employeeId);
        if ($employee->photo && file_exists(public_path($employee->photo))) {
            unlink(public_path($employee->photo));
        }    
        $this->employeeRepository->delete($employeeId);
        $isSecretary =  $this->secretaryRepository->getByEmail($employee->email);
        if ($isSecretary) {
            $this->secretaryRepository->delete($isSecretary->id);
            
        }
            return response()->json(["message" => "Employee has been deleted successfuly "],200);      

    }
}