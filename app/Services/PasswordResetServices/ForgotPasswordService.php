<?php
namespace App\Services\PasswordResetServices;

use App\Models\Student;
use App\Models\Trainer;
use App\Models\Secretary;
use App\Mail\SendCodeResetPassword;
use Illuminate\Support\Facades\Mail;
use App\Repositories\StudentRepository;
use App\Repositories\TrainerRepository;
use App\Repositories\SecretaryRepository;
use App\Repositories\PasswordResetRepository;

class ForgotPasswordService 
{
    protected $ResetPasswordRepository;
    protected $secretaryRepository;
    protected $studentRepository;
    protected $trainerRepository;
    public function __construct(PasswordResetRepository  $ResetPasswordRepository,SecretaryRepository  $secretaryRepository,StudentRepository  $studentRepository,TrainerRepository  $trainerRepository)
    {
        $this->ResetPasswordRepository = $ResetPasswordRepository;
        $this->secretaryRepository = $secretaryRepository;
        $this->studentRepository = $studentRepository;
        $this->trainerRepository = $trainerRepository;
    }

    
    protected function getModelFromType(string $type): string
    {
        return match ($type) {
            'student' => \App\Models\Student::class,
            'secretary' => \App\Models\Secretary::class,
            'trainer' => \App\Models\Trainer::class,
            default => throw new \Exception("Undefined"),
        };
    }

    
    public function reforgotPassword($email)
    {
        $type = request()->segment(3);
        $modelClass  = $this->getModelFromType($type);
        try {
                if ($modelClass ==  \App\Models\Student::class ) {
                    $user = $this->studentRepository->getByEmail($email);
                
                } elseif ($modelClass == \App\Models\Secretary::class) 
                        {
                        $user = $this->secretaryRepository->getByEmail($email);
                                }   else {
                    
                                $user = $this->trainerRepository->getByEmail($email);}
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Email not found'], 404);
        }

        $this->ResetPasswordRepository->deleteRecord($email);
        $token = $this->ResetPasswordRepository->generationToken();
        $arrData = ['email' => $email ,'token' => $token ,'modelType' =>  $modelClass ,'created_at' => now()];
        $this->ResetPasswordRepository->store($arrData);
        Mail::to($email)->send(new SendCodeResetPassword($user,$token));
        return response()->json(["mes"=>" Verification code sent successfully. "]);
    }

    
}