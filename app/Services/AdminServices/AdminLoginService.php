<?php
namespace App\Services\AdminServices;

use App\Models\Admin;
use Illuminate\Support\Arr;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class AdminLoginService 
{
    protected $model;
    function __construct(Admin $model)
    {
        $this->model =$model;
    }

    function validation($request)
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

        if (!$token = auth()->guard('admin')->attempt($credentials)) {
            return response()->json(['error' => 'InValidData'], 422);
        }
        
       /** @var \App\Models\Admin $admin */
        $admin = auth()->guard('admin')->user();
        $admin->fcm_token = $validated['fcm_token'];
        $admin->save();

        return $token;
    }



    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'admin' => auth()->user(),
        ]);
    }

    function Login($request)
    {
        
        $userdata = $this->validation($request);
        $usertoken = $this->IsValidData($userdata);
        if ($usertoken instanceof JsonResponse && $usertoken->getStatusCode() === 422) {
            return response()->json(['email' => 'The selected email is invalid.'], 401);
        }
        $data = $this->createNewToken($usertoken);
        return response()->json(["Message" => "User successfully signed in","data" => $data -> original]);
    }

}