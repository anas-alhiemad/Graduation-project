<?php
namespace App\Services\StudentServices;

use Illuminate\Http\JsonResponse;
use App\Repositories\StudentRepository;
use Illuminate\Support\Facades\Validator;


class StudentLoginService 
{
    protected $studentRepository;
    
    public function __construct(StudentRepository  $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    function validation ($request)
    {
        $validator = Validator::make($request->all(),$request->rules());
         if ($validator->fails()){
            return response()->json($validator->errors(), 422);}

        return $validator;
    }


    function IsValidData($data)
    {
        $validatorArray = [
            'email' => $data->email,
            'password' => $data->password];

        if (! $token = auth()->guard('student')->attempt($validatorArray))
        {
            return response()->json(['error' => 'InValidData'], 401);
        }
        return $token;
    }


    
    function IsVerified($email)
    {
        return $this->studentRepository->getVerificationToken($email);
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => auth()->guard('student')->user(),
        ]);
    }

    protected function storeFcm_Token($student,$fcmToken)
    {
        $this->studentRepository->storeFcmToken($student,$fcmToken);
    }

    public function Login($request)
    {
        // $userdata = $this->validation($request);
        $studenttoken = $this->IsValidData($request);
        if ($studenttoken instanceof JsonResponse && $studenttoken->getStatusCode() === 401)
        {
            return response()->json(['email' => 'The selected email is invalid.'], 401);
        }
        if($this ->IsVerified($request->email) == null) 
        {
            return response()->json(["Message" => "your account not verified"],422);
        }

        $accessToken = $this->createNewToken($studenttoken);
        $student = auth()->guard('student')->user();
        $this -> storeFcm_Token($student,$request->fcm_token);
        return response()->json(["Message" => "successfuly","AccessToken"=>$accessToken->original],200);
    }

}