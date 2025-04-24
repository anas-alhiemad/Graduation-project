<?php
namespace App\Services\PasswordResetServices;

use App\Repositories\StudentRepository;
use App\Repositories\TrainerRepository;
use App\Repositories\SecretaryRepository;
use App\Repositories\PasswordResetRepository;
use Mockery\Expectation;

class PasswordResetService 
{
    protected $resetPasswordRepository;
    protected $secretaryRepository;
    protected $studentRepository;
    protected $trainerRepository;
    
    public function __construct(PasswordResetRepository  $resetPasswordRepository,SecretaryRepository  $secretaryRepository,StudentRepository  $studentRepository,TrainerRepository  $trainerRepository)
    {
        $this->resetPasswordRepository = $resetPasswordRepository;
        $this->secretaryRepository = $secretaryRepository;
        $this->studentRepository = $studentRepository;
        $this->trainerRepository = $trainerRepository;
    }

    public function resetPassword($request) 
    {

        try {
             $record = $this->resetPasswordRepository->getRecord($request->token);
             $password=bcrypt($request->password);
            if (!$record) {
                return response(['message' => trans('passwords.code_is_expire')], 422);
            
            }elseif ($record->modelType =="App\Models\Student" ) {
                $user = $this->studentRepository->updatePassword($record->email,$password);
                $record->delete();
                
            } elseif ($record->modelType =="App\Models\Secretary") {
                    $user = $this->secretaryRepository->updatePassword($record->email,$password);
                    $record->delete();

            } else {
                
                $user = $this->trainerRepository->updatePassword($record->email,$password);
                $record->delete();
            }
            return response(['message' =>'password has been successfully reset'], 200);

            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json(['error' => 'token not found'], 404);
            }
    }
}