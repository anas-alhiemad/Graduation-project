<?php
namespace App\Services\secretaryServices;

use Illuminate\Support\Arr;
use Illuminate\Http\JsonResponse;
use App\Repositories\SecretaryRepository;
use Illuminate\Support\Facades\Validator;

class SecretaryLoginService 
{
    protected $secretaryRepository;
    
    public function __construct(SecretaryRepository  $secretaryRepository)
    {
        $this->secretaryRepository = $secretaryRepository;
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
        $validated = $data->validated();
        $credentials = Arr::only($validated, ['email', 'password']);

        if (!$token = auth()->guard('secretary')->attempt($credentials)) {
            return response()->json(['error' => 'InValidData'], 422);
        }
        
       /** @var \App\Models\Secretary $secretary */
        $secretary = auth()->guard('secretary')->user();
        $secretary->fcm_token = $validated['fcm_token'];
        $secretary->save();

        return $token;
    }


    function IsVerified($email)
    {
        return $this->secretaryRepository->getVerificationToken($email);
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => auth()->guard('secretary')->user(),
        ]);
    }
    // protected function storeFcm_Token($secretary,$fcmToken)
    // {
    //     $this->secretaryRepository->storeFcmToken($secretary,$fcmToken);
    // }


    
    public function Login($request)
    {
        // $userdata = $this->validation($request);
        $secretarytoken = $this->IsValidData($request);
        if ($secretarytoken instanceof JsonResponse && $secretarytoken->getStatusCode() === 422)
        {
            return response()->json(['email' => 'The selected email is invalid.'], 401);
        }
        if($this ->IsVerified($request->email) == null) 
        {
            return response()->json(["Message" => "your account not verified"],422);
        }

        $accessToken = $this->createNewToken($secretarytoken);
        $secretary = auth()->guard('secretary')->user();
   //     $this -> storeFcm_Token($secretary,$request->fcm_token);
        return response()->json(["Message" => "successfuly","AccessToken"=>$accessToken->original],200);
    }

}