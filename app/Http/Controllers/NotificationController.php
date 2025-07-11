<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NotificationServices\DisplayNotificationsService;

class NotificationController extends Controller
{
    protected $displayNotificationsService;

    public function __construct(DisplayNotificationsService $displayNotificationsService)
    {
        $this->displayNotificationsService = $displayNotificationsService;
    }

    public function IndexNotifications()
    {
        return $this->displayNotificationsService->getNotificationsForCurrentUser();
    }
}
