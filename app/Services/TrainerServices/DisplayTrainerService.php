<?php
namespace App\Services\TrainerServices;

use App\Repositories\TrainerRepository;

class DisplayTrainerService 
{
    protected $trainerRepository;
    
    public function __construct(TrainerRepository  $trainerRepository)
    {
        $this->trainerRepository = $trainerRepository;
    }

    
    public function indexTrainer() 
    {
        $trainers = $this->trainerRepository->getAll();
        return response()->json([
            "message" => "All Trainers in the System.",
            "Trainers" => $trainers]);
    }

    public function getTrainerById($trainerId)
    {
        $trainer = $this->trainerRepository->getById($trainerId);
        return response()->json([
            "message" => "the trainer .",
            "Trainer" => $trainer]);
    }


    public function searchTrainer($request)
    {

        $trainers = $this->trainerRepository->search($request);

        return response()->json(["message"=>"Search results","Trainers" => $trainers]);
    }

}