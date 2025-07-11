<?php
namespace App\Services\NotificationServices;

use Illuminate\Support\Facades\Auth;


class DisplayNotificationsService 
{
    public function getNotificationsForCurrentUser()
    {
        $user = Auth::guard('student')->user() ?? Auth::guard('trainer')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $notifications = $user->notifications()->latest()->get();

        return response()->json([
            'message' => 'Your notifications',
            'notifications' => $notifications
        ], 200);
    }
}