<?php
namespace App\Services\AdminServices;

use App\Mail\verificationEmail;
use Illuminate\Support\Facades\Mail;
use App\Repositories\SecretaryRepository;

class RegistrationSecretaryService 
{
    protected $secretaryRepository;
    
    public function __construct(SecretaryRepository  $secretaryRepository)
    {
        $this->secretaryRepository = $secretaryRepository;
    }

    
    function store($request)
    {
        $secretary = array_merge($request->validated(),
            ['password' => bcrypt($request->password),
            'photo' => 'upload/'.$request->file('photo')->store('secretaryPhoto','public_upload')
            ]);

        $secretaryCreated = $this->secretaryRepository->create($secretary);    
        return $secretaryCreated;
    }

    function generateToken($secretaryEmail)
    {
        return $this->secretaryRepository->storeToken($secretaryEmail);
    }


    function SendEmail($secretary)
    {
        Mail::to($secretary->email)->send(new verificationEmail($secretary));
    }


    function  register($request)
    {
        $secretary = $this->store($request);
        
        $studentToken = $this->generateToken($secretary->email);
        
        $this->SendEmail($studentToken);
        
        return response()->json(["Message"=>"Secretary has been successfully registered in the system","secretaryInfo"=>$secretary]);

    }
}