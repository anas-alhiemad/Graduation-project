<?php
namespace App\Services\secretaryServices;

use App\Repositories\SecretaryRepository;

class SecretaryVerificationEmailService 
{
    protected $secretaryRepository;
    
    public function __construct(SecretaryRepository  $secretaryRepository)
    {
        $this->secretaryRepository = $secretaryRepository;
    }


    public function verificationEmail($token)
    {
        $response =  $this->secretaryRepository->verification_tokenToken($token);
        if (!$response){ 
            return response()->json(["Message" => "this token is invalid"], 400);}

        return response()->json(["Message" => "your account has been verified "], 200);
    }  

    

}