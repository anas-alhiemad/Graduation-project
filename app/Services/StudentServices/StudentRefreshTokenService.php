<?php
namespace App\Services\StudentServices;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Repositories\StudentRepository;

class StudentRefreshTokenService 
{
    protected $studentRepository;
    
    public function __construct(StudentRepository  $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

        
    public function refreshToken() 
    {
        $newToken = JWTAuth::refresh(JWTAuth::getToken());
        return response()->json([
            'success' => "the new token",
            'token' => $newToken,
        ], 200);
    }    

    public function studentProfile($studentId) 
    {
        $student = $this->studentRepository->getById($studentId);
        return response()->json(["student" => $student]);
    }
}