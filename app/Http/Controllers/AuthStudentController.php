<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StudentServices\StudentLoginService;
use App\Services\StudentServices\StudentRegisterService;
use App\Http\Requests\StudentRequest\LoginStudentRequest;
use App\Http\Requests\StudentRequest\RegisterStudentRequest;
use App\Services\StudentServices\StudentRefreshTokenService;
use App\Http\Requests\StudentRequest\VerificationEmailRequest;
use App\Services\StudentServices\StudentVerificationEmailService;

class AuthStudentController extends Controller
{

    protected $verificationEmailStudentService;
    protected $studentLoginService; //,UserLoginService $userLoginService
    protected $studentRefreshTokenService; 

    public function __construct(StudentVerificationEmailService $verificationEmailStudentService,StudentLoginService $studentLoginService,StudentRefreshTokenService $studentRefreshTokenService)
    {
        $this->verificationEmailStudentService = $verificationEmailStudentService;
        $this->studentLoginService = $studentLoginService;
        $this->studentRefreshTokenService = $studentRefreshTokenService;
    }

    public function VerificationEmail(VerificationEmailRequest $request)
    {
        return $this->verificationEmailStudentService->verificationEmail($request->token);
    }

    public function Login(LoginStudentRequest $request)
    {
        return $this->studentLoginService->Login($request);
    }

    public function RefreshToken() 
    {
        return $this->studentRefreshTokenService->refreshToken();
    }
    
    public function GetStudentProfile($studentId) 
    {
        return $this->studentRefreshTokenService->studentProfile($studentId);
    }

    public function Logout()
    {
        auth()->guard('student')->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
}
