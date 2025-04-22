<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StudentRequest\VerificationEmailRequest;
use App\Services\secretaryServices\StudentRegistrationService;
use App\Services\secretaryServices\TrainerRegistrationService;
use App\Http\Requests\StudentRequest\RegistrationStudentRequest;
use App\Http\Requests\TrainerRequest\RegistrationTrainerRequest;

class FunctionSecretaryController extends Controller
{

    protected $studentRegistrationService;
    protected $trainerRegistrationService;

    public function __construct(StudentRegistrationService $studentRegistrationService,TrainerRegistrationService $trainerRegistrationService)
    {
        $this->studentRegistrationService = $studentRegistrationService;
        $this->trainerRegistrationService = $trainerRegistrationService;
    }

    public function StudentRegistration(RegistrationStudentRequest $request) 
    {
        return $this->studentRegistrationService->register($request);
    }

    
    public function TrainerRegistration(RegistrationTrainerRequest $request) 
    {
        return $this->trainerRegistrationService->register($request);
    }
    
}
