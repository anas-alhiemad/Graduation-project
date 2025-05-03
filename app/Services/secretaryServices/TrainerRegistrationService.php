<?php
namespace App\Services\secretaryServices;

use App\Mail\verificationEmail;
use Illuminate\Support\Facades\Mail;
use App\Repositories\TrainerRepository;
use Illuminate\Support\Facades\Validator;

class TrainerRegistrationService 
{
    protected $trainerRepository;
    
    public function __construct(TrainerRepository  $trainerRepository)
    {
        $this->trainerRepository = $trainerRepository;
    }

    
    function validation ($request)
    {
        $validator = Validator::make($request->all(),$request->rules());
         if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
         return $validator;
    }


    function store($request)
    {
        $trainerPhoto = null;
        if ($request->hasFile('photo')) {
            $trainerPhoto = 'upload/' . $request->file('photo')->store('trainerPhoto', 'public_upload');
        }
        $trainer = array_merge($request->validated(),
            ['password' => bcrypt($request->password),
            'photo' =>  $trainerPhoto
            ]);

        $trainerCreated = $this->trainerRepository->create($trainer);    
        return $trainerCreated;
    }

    function SendEmail($trainer)
    {
        Mail::to($trainer ->email)->send(new verificationEmail($trainer));
        
    }

    
    function generateToken($trainerEmail)
    {
        return $this->trainerRepository->storeToken($trainerEmail);
    }


    function  register($request)
    {
            $data = $this->validation($request);
            
            $trainer = $this->store($request);
            
            $trainerToken = $this->generateToken($trainer->email);
            
            $this->SendEmail($trainerToken);
            
            return response()->json(["Message"=>"Trainer has been successfully registered in the system","User"=>$trainer]);

    }

}