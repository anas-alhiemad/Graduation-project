<?php
namespace App\Services\AdminServices;

use App\Mail\verificationEmail;
use Illuminate\Support\Facades\Mail;
use App\Repositories\EmployeeRepository;
use App\Repositories\SecretaryRepository;

class RegistrationSecretaryService 
{
    protected $secretaryRepository;
    protected $employeeRepository;
    
    public function __construct(SecretaryRepository  $secretaryRepository,EmployeeRepository $employeeRepository)
    {
        $this->secretaryRepository = $secretaryRepository;
        $this->employeeRepository = $employeeRepository;
    }

    
    function store($request)
    {
        $secretaryPhoto = null ;
        if ($request->hasFile('photo')) {
            $secretaryPhoto = 'upload/' . $request->file('photo')->store('employeePhoto', 'public_upload');
        }
        $secretary = array_merge($request->validated(),
            ['password' => bcrypt($request->password),
            'photo' => $secretaryPhoto
            ]);

        $secretaryCreated = $this->secretaryRepository->create($secretary);
        $secretaryInfo = $request->except('password');
        $secretaryInfo['role'] = "secretary";
        $secretaryInfo['photo'] = $secretaryPhoto;
        $this->employeeRepository->create($secretaryInfo);
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