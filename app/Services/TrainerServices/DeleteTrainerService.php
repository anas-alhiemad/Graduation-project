<?php
namespace App\Services\TrainerServices;

use App\Repositories\TrainerRepository;

class DeleteTrainerService 
{
    protected $trainerRepository;
    
    public function __construct(TrainerRepository  $trainerRepository)
    {
        $this->trainerRepository = $trainerRepository;
    }


    public function deletingTrainer($trainerId) 
    {
        $trainer = $this->trainerRepository->getId($trainerId);
        if ($trainer->photo && file_exists(public_path($trainer->photo))) {
            unlink(public_path($trainer->photo));
        }
        $trainerInfo = $this->trainerRepository->delete($trainerId);
        return response()->json(["message" => "trainer has been deleted successfuly "],200);      

    }
}