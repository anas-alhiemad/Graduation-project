<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AdminServices\AdminLoginService;
use App\Http\Requests\AdminRequest\LoginAdminRequest;
use App\Services\NotificationService\SendNotificationsService;

class AuthAdminController extends Controller
{
    protected $adminLoginService;

    public function __construct(AdminLoginService $adminLoginService)
    {
        $this->adminLoginService = $adminLoginService;
    }

    public function login(LoginAdminRequest $request)
    {
    	return $this->adminLoginService->Login($request);
    }

    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }


        public function sendNotification()
        {
            $service = new SendNotificationsService();
            $fcmToken ="eU5Co4zpjKyKSy4Ket_O66:APA91bEW7TGqHlLRaWVGJyKHdpPBawszC8tBjMpo7BBS-IhTHxFO661z6We-rhltCDb2RedRPnG8RzMgLMJ9FXr4g7zqhuCFnWTgEuDcaePDEY-LynlDDC0";
            $messageData = [
                'title' => 'Hello Test Notification',
                'body' => 'This is a test notification sent via FCM.',
            ];
            $response = $service->sendByFcm($fcmToken, $messageData);
        // $accessToken =$service->getAccessToken();

            return response()->json(["mes"=>"done"]);
        }

}
