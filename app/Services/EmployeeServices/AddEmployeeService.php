<?php
namespace App\Services\EmployeeServices;

use App\Repositories\EmployeeRepository;
use App\Repositories\SecretaryRepository;

class AddEmployeeService 
{

    protected $employeeRepository;
    
    public function __construct(EmployeeRepository  $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function store($request) 
    {
        $employeeInfo = $request->all();
        if ($request->hasFile('photo')) {
            $employeeInfo['photo'] = 'upload/' . $request->file('photo')->store('employeePhoto', 'public_upload');
        }
        $employeeAdded =$this->employeeRepository->create($employeeInfo);
        return response()->json([
            "message" => "Employee has been created successfuly ",
            "group" => $employeeAdded],200);
    }
}