<?php
namespace App\Services\secretaryServices;

use App\Mail\verificationEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use App\Repositories\StudentRepository;
use Illuminate\Support\Facades\Validator;

class StudentRegistrationService 
{
    protected $studentRepository;
    
    public function __construct(StudentRepository  $studentRepository)
    {
        $this->studentRepository = $studentRepository;
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
        $studentPhoto = null;
        if ($request->hasFile('photo')) {
            $studentPhoto = 'upload/' . $request->file('photo')->store('studentPhoto', 'public_upload');
        }
        $student = array_merge($request->validated(),
            ['password' => bcrypt($request->password),
             'photo' => $studentPhoto
            ]);

        $studentCreated = $this->studentRepository->create($student);    
        return $studentCreated;
    }

    function SendEmail($student)
    {
        Mail::to($student ->email)->send(new verificationEmail($student));
        
    }

    
    function generateToken($studentEmail)
    {
        return $this->studentRepository->storeToken($studentEmail);
    }


    function  register($request)
    {
            $data = $this->validation($request);
            
            $student = $this->store($request);
            
            $studentToken = $this->generateToken($student->email);
            
            $this->SendEmail($studentToken);
            
            return response()->json(["Message"=>"Student has been successfully registered in the system","User"=>$student]);

    }

}