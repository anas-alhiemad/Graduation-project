<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AdminServices\AdminLoginService;
use App\Http\Requests\AdminRequest\LoginAdminRequest;

class AuthAdminController extends Controller
{
    protected $adminLoginService;

    public function __construct(AdminLoginService $adminLoginService)
    {
        $this->adminLoginService = $adminLoginService;
    }

    public function login(LoginAdminRequest $request)
    {
    	return $this->adminLoginService->Login($request);
    }

    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }
}
