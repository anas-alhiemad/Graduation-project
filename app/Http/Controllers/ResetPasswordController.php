<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ResetPassword\ResetPasswordRequest;
use App\Http\Requests\ResetPassword\ForgotPasswordRequest;
use App\Services\PasswordResetServices\PasswordResetService;
use App\Services\PasswordResetServices\ForgotPasswordService;

class ResetPasswordController extends Controller
{
    protected $forgotPasswordService;
    protected $passwordResetService;

    public function __construct(ForgotPasswordService $forgotPasswordService,PasswordResetService $passwordResetService)
    {
        $this->forgotPasswordService = $forgotPasswordService;
        $this->passwordResetService = $passwordResetService;
    }

    public function ForgotPassword(ForgotPasswordRequest $request) 
    {
        return $this->forgotPasswordService->reforgotPassword($request->email);
    }

    public function PasswordReset(ResetPasswordRequest $request) 
    {
        return $this->passwordResetService->resetPassword($request);
    }
}
