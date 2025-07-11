<?php
namespace App\Services\NotificationServices;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
// use App\Services\NotificationService\NotificationsService;

class SendNotificationsService
{
    protected $apiUrl;
    protected $credentialsFilePath;

    public function __construct()
    {

        $this->apiUrl = 'https://fcm.googleapis.com/v1/projects/graduationproject-3d3b0/messages:send';
        $this->credentialsFilePath = storage_path('app/json/graduationproject-3d3b0-firebase-adminsdk-fbsvc-84c220f61d.json');
    }

    public function sendByFcm(string $fcmToken, array $messageData)
    {
        try {
            $accessToken = $this->getAccessToken();
            $message = $this->buildMessage($fcmToken, $messageData);

            $response = Http::withHeader('Authorization', 'Bearer ' . $accessToken)
                            ->post($this->apiUrl, $message);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('FCM send notification failed', [
                'response' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send notification',
                'error' => $response->body(),
            ];
        } catch (\Exception $e) {
            Log::error('Error in sendByFcm method', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ];
        }
    }

    public function getAccessToken()
    {
        return Cache::remember('access_token', now()->addHour(), function () {
           $client = new \Google_Client();
            $client->setAuthConfig($this->credentialsFilePath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->fetchAccessTokenWithAssertion();
            $token = $client->getAccessToken();
            return $token['access_token'];
        });
    }

    protected function buildMessage(string $fcmToken, array $messageData)
    {
        return [
            "message" => [
                "token" => $fcmToken,
                "notification" => [
                    "title" => $messageData['title'],
                    "body" => $messageData['body'],
                ],
            ],
        ];
    }


    public function storeNotification($notifiable, $title, $body)
    {
        return $notifiable->notifications()->create([
            'title' => $title,
            'body' => $body,
        ]);
    }

}


