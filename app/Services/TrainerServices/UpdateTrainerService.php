<?php
namespace App\Services\TrainerServices;


use App\Repositories\TrainerRepository;

class UpdateTrainerService 
{
    protected $trainerRepository;
    
    public function __construct(TrainerRepository  $trainerRepository)
    {
        $this->trainerRepository = $trainerRepository;
    }


    public function updatingTrainer($trainerId,$request) 
    {
        $trainer = $this->trainerRepository->getById($trainerId);
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            if ($trainer->photo && file_exists(public_path($trainer->photo))) {
                unlink(public_path($trainer->photo));
            }
            $data['photo'] = 'upload/' . $request->file('photo')->store('trainerPhoto', 'public_upload');
        }
        $trainerInfo = $this->trainerRepository->update($trainerId,$data);
        return response()->json(["message" => "Trainer has been Updated successfuly ","TrainerInfo" => $trainerInfo],200);      

    }
}