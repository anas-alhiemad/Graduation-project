<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\EmployeeRequest\AddEmployeeRequest;
use App\Services\AdminServices\RegistrationSecretaryService;
use App\Http\Requests\SeceretaryRequest\RegistrationSecretaryRequest;

class FunctionAdminController extends Controller
{
    protected $registrationSecretaryService;

    public function __construct(RegistrationSecretaryService $registrationSecretaryService)
    {
        $this->registrationSecretaryService = $registrationSecretaryService;
    }


    public function RegistrationSecretary(RegistrationSecretaryRequest $request) 
    {
        return $this->registrationSecretaryService->register($request);
    }

}
