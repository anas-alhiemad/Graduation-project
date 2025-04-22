<?php
namespace App\Services\StudentServices;

use App\Repositories\StudentRepository;

class StudentVerificationEmailService 
{
    protected $studentRepository;
    
    public function __construct(StudentRepository  $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }


    public function verificationEmail($token)
    {
        $response =  $this->studentRepository->verification_tokenToken($token);
        if (!$response){ 
            return response()->json(["Message" => "this token is invalid"], 400);}

        return response()->json(["Message" => "your account has been verified "], 200);
    }

    

}