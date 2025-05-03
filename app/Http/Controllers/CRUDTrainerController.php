<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TrainerServices\DeleteTrainerService;
use App\Services\TrainerServices\UpdateTrainerService;
use App\Services\TrainerServices\DisplayTrainerService;
use App\Http\Requests\TrainerRequest\UpdateTrainerRequest;


class CRUDTrainerController extends Controller
{
    protected $updateTrainerService;
    protected $deleteTrainerService;
    protected $displayTrainerService;

    public function __construct(DisplayTrainerService $displayTrainerService,UpdateTrainerService $updateTrainerService,DeleteTrainerService $deleteTrainerService)
    {
        $this->updateTrainerService = $updateTrainerService;
        $this->deleteTrainerService = $deleteTrainerService;
        $this->displayTrainerService = $displayTrainerService;
    }

    public function ShowAllTrainer() 
    {
        return $this->displayTrainerService->indexTrainer();
    }
    public function ShowTrainerById($trainerId) 
    {
        return $this->displayTrainerService->getTrainerById($trainerId);
    }

    public function UpdateTrainer($trainerId,UpdateTrainerRequest $request) 
    {
        return $this->updateTrainerService->updatingTrainer($trainerId,$request);
    }

    public function DeleteTrainer($trainerId) 
    {
        return $this->deleteTrainerService->deletingtrainer($trainerId);
    }
    
    public function SearchTrainer($querySearch) 
    {
        return $this->displayTrainerService->SearchTrainer($querySearch);
    }

}
