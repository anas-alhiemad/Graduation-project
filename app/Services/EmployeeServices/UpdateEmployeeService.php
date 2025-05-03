<?php
namespace App\Services\EmployeeServices;

use App\Repositories\EmployeeRepository;
use App\Repositories\SecretaryRepository;

class UpdateEmployeeService 
{
    protected $employeeRepository;
    protected $secretaryRepository;
    public function __construct(EmployeeRepository  $employeeRepository,SecretaryRepository  $secretaryRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->secretaryRepository = $secretaryRepository;
    }

    public function updatingEmployee($employeeId,$request)
    {
        $employee = $this->employeeRepository->getById($employeeId);
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            if ($employee->photo && file_exists(public_path($employee->photo))) {
                unlink(public_path($employee->photo));
            }
            $data['photo'] = 'upload/' . $request->file('photo')->store('employeePhoto', 'public_upload');
        }
            
        $employeeInfo =  $this->employeeRepository->update($employeeId,$data);
        $isSecretary =  $this->secretaryRepository->getByEmail($employeeInfo->email);
        if ($isSecretary) {
            $this->secretaryRepository->update($isSecretary->id,$data);
            
            // if ($request->has('role')&&$request->role!="secretary") {
            //     if ($isSecretary->photo && file_exists(public_path($isSecretary->photo))) {
            //         unlink(public_path($isSecretary->photo));
            //     }    
            //     $this->secretaryRepository->delete($employeeId);
            //     return response()->json(["message" => "Employee has been Updated successfuly ","employeeInfo" => $employeeInfo],200);      
            // }
        }
        return response()->json(["message" => "Employee has been Updated successfuly ","employeeInfo" => $employeeInfo],200);      

    }

}