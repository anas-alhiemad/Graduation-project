<?php
namespace App\Services\TrainerServices;

use App\Repositories\TrainerRepository;

class TrainerVerificationEmailService 
{
    protected $trainerRepository;
    
    public function __construct(TrainerRepository  $trainerRepository)
    {
        $this->trainerRepository = $trainerRepository;
    }


    public function verificationEmail($token)
    {
        $response =  $this->trainerRepository->verification_tokenToken($token);
        if (!$response){ 
            return response()->json(["Message" => "this token is invalid"], 400);}

        return response()->json(["Message" => "your account has been verified "], 200);
    }

}