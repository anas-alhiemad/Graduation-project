<?php
namespace App\Services\TrainerServices;

use Illuminate\Http\JsonResponse;
use App\Repositories\TrainerRepository;
use Illuminate\Support\Facades\Validator;

class TrainerLoginService 
{
    protected $trainerRepository;
    
    public function __construct(TrainerRepository  $trainerRepository)
    {
        $this->trainerRepository = $trainerRepository;
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

        if (! $token = auth()->guard('trainer')->attempt($validatorArray))
        {
            return response()->json(['error' => 'InValidData'], 401);
        }
        return $token;
    }


    
    function IsVerified($email)
    {
        return $this->trainerRepository->getVerificationToken($email);
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => auth()->guard('trainer')->user(),
        ]);
    }

    protected function storeFcm_Token($trainer,$fcmToken)
    {
        $this->trainerRepository->storeFcmToken($trainer,$fcmToken);
    }

    public function Login($request)
    {
        // $userdata = $this->validation($request);
        $trainertoken = $this->IsValidData($request);
        if ($trainertoken instanceof JsonResponse && $trainertoken->getStatusCode() === 401)
        {
            return response()->json(['email' => 'The selected email is invalid.'], 401);
        }
        if($this ->IsVerified($request->email) == null) 
        {
            return response()->json(["Message" => "your account not verified"],422);
        }

        $accessToken = $this->createNewToken($trainertoken);
        $trainer = auth()->guard('trainer')->user();
        $this -> storeFcm_Token($trainer,$request->fcm_token);
        return response()->json(["Message" => "successfuly","AccessToken"=>$accessToken->original],200);
    }

}