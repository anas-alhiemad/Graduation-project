<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\secretaryServices\SecretaryLoginService;
use App\Http\Requests\SeceretaryRequest\LoginSecretaryRequest;
use App\Http\Requests\StudentRequest\VerificationEmailRequest;
use App\Services\secretaryServices\SecretaryVerificationEmailService;

class AuthSecretaryController extends Controller
{
    protected $secretaryVerificationEmailService;
    protected $secretaryLoginService;

    public function __construct(SecretaryVerificationEmailService $secretaryVerificationEmailService,SecretaryLoginService $secretaryLoginService)
    {
        $this->secretaryVerificationEmailService = $secretaryVerificationEmailService;
        $this->secretaryLoginService = $secretaryLoginService;
    }

    public function VerificationEmail (VerificationEmailRequest $request) 
    {
        return $this->secretaryVerificationEmailService->verificationEmail($request->token);
    }

    public function Login(LoginSecretaryRequest $request)
    {
        return $this->secretaryLoginService->Login($request);
    }


    public function Logout()
    {
        auth()->guard('secretary')->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
}
