<?php
namespace App\Services\NotificationServices;

use Illuminate\Support\Facades\Auth;


class DisplayNotificationsService 
{
    public function getNotificationsForCurrentUser()
    {
        $user = Auth::guard('student')->user() ?? Auth::guard('trainer')->user() ?? Auth::guard('secretary')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        /**
         * @var \App\Models\Student|\App\Models\Trainer|\App\Models\Secretary $user
         */
        $notifications = $user->notifications()->latest()->get();

        return response()->json([
            'message' => 'Your notifications',
            'notifications' => $notifications
        ], 200);
    }
}