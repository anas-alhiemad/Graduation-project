<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TrainerServices\TrainerLoginService;
use App\Http\Requests\TrainerRequest\LoginTrainerRequest;
use App\Http\Requests\StudentRequest\VerificationEmailRequest;
use App\Services\TrainerServices\TrainerVerificationEmailService;

class AuthTrainerController extends Controller
{
    protected $trainerVerificationEmailService;
    protected $trainerLoginService;

    public function __construct(TrainerVerificationEmailService $trainerVerificationEmailService,TrainerLoginService $trainerLoginService)
    {
        $this->trainerVerificationEmailService = $trainerVerificationEmailService;
        $this->trainerLoginService = $trainerLoginService;
    }

    public function VerificationEmail(VerificationEmailRequest $request)
    {
        return $this->trainerVerificationEmailService->verificationEmail($request->token);
    }


    public function Login(LoginTrainerRequest $request)
    {
        return $this->trainerLoginService->Login($request);
    }

    public function Logout()
    {
        auth()->guard('trainer')->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
}
